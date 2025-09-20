<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePollRequest;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PollController extends Controller
{
    /**
     * Display a listing of the user's polls.
     */
    public function index(): Response
    {
        // Get real polls from database for the authenticated user
        $polls = Poll::with('options')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($poll) {
                return [
                    'id' => $poll->id,
                    'user_id' => $poll->user_id,
                    'question' => $poll->question,
                    'slug' => $poll->slug,
                    'expires_at' => $poll->expires_at?->toISOString(),
                    'created_at' => $poll->created_at->toISOString(),
                    'updated_at' => $poll->updated_at->toISOString(),
                    'is_expired' => $poll->isExpired(),
                    'total_votes' => $poll->total_votes,
                    'options_count' => $poll->options->count(),
                    'options' => $poll->options->map(function ($option) {
                        return [
                            'id' => $option->id,
                            'option_text' => $option->option_text,
                            'vote_count' => $option->vote_count,
                        ];
                    }),
                ];
            });

        return Inertia::render('Dashboard', [
            'polls' => $polls
        ]);
    }

    /**
     * Show the form for creating a new poll.
     */
    public function create(): Response
    {
        return Inertia::render('polls/Create');
    }

    /**
     * Store a newly created poll in storage.
     */
    public function store(StorePollRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            // Get validated data with additional processing (slug, user_id, etc.)
            $validatedData = $request->validated();
            
            // Extract options from validated data
            $options = $validatedData['options'];
            unset($validatedData['options']);
            
            // Create the poll
            $poll = Poll::create($validatedData);
            
            // Create poll options
            foreach ($options as $optionText) {
                PollOption::create([
                    'poll_id' => $poll->id,
                    'option_text' => trim($optionText),
                ]);
            }
            
            DB::commit();
            
            // Load the poll with its options for response
            $poll->load('options');
            
            return response()->json([
                'success' => true,
                'message' => 'Poll created successfully!',
                'data' => [
                    'poll' => [
                        'id' => $poll->id,
                        'user_id' => $poll->user_id,
                        'question' => $poll->question,
                        'slug' => $poll->slug,
                        'expires_at' => $poll->expires_at?->toISOString(),
                        'created_at' => $poll->created_at->toISOString(),
                        'updated_at' => $poll->updated_at->toISOString(),
                        'is_expired' => $poll->isExpired(),
                        'total_votes' => $poll->total_votes,
                        'options_count' => $poll->options->count(),
                        'options' => $poll->options->map(function ($option) {
                            return [
                                'id' => $option->id,
                                'option_text' => $option->option_text,
                                'vote_count' => $option->vote_count,
                            ];
                        }),
                    ]
                ]
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error for debugging
            \Log::error('Poll creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create poll. Please try again.',
                'errors' => ['general' => 'An error occurred while creating the poll.']
            ], 500);
        }
    }

    /**
     * Display the specified poll.
     */
    public function show(Poll $poll): Response
    {
        // Load poll with options and vote counts
        $poll->load('options');
        
        // Format poll data for frontend
        $pollData = [
            'id' => $poll->id,
            'user_id' => $poll->user_id,
            'question' => $poll->question,
            'slug' => $poll->slug,
            'expires_at' => $poll->expires_at?->toISOString(),
            'created_at' => $poll->created_at->toISOString(),
            'updated_at' => $poll->updated_at->toISOString(),
            'is_expired' => $poll->isExpired(),
            'total_votes' => $poll->total_votes,
            'options_count' => $poll->options->count(),
            'options' => $poll->options->map(function ($option) {
                return [
                    'id' => $option->id,
                    'option_text' => $option->option_text,
                    'vote_count' => $option->vote_count,
                ];
            }),
        ];
        
        return Inertia::render('polls/Vote', [
            'poll' => $pollData
        ]);
    }

    /**
     * Show the form for editing the specified poll.
     */
    public function edit(Poll $poll): Response
    {
        // TODO: Implement poll editing
        return Inertia::render('polls/Edit', [
            'poll' => $poll
        ]);
    }

    /**
     * Update the specified poll in storage.
     */
    public function update(StorePollRequest $request, Poll $poll): JsonResponse
    {
        try {
            // Check if user owns the poll
            if ($poll->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this poll.',
                ], 403);
            }

            DB::beginTransaction();
            
            // Get validated data
            $validatedData = $request->validated();
            $options = $validatedData['options'];
            unset($validatedData['options']);
            
            // Update the poll
            $poll->update($validatedData);
            
            // Delete existing options and create new ones
            $poll->options()->delete();
            foreach ($options as $optionText) {
                PollOption::create([
                    'poll_id' => $poll->id,
                    'option_text' => trim($optionText),
                ]);
            }
            
            DB::commit();
            
            // Load updated poll with options
            $poll->load('options');
            
            return response()->json([
                'success' => true,
                'message' => 'Poll updated successfully!',
                'data' => [
                    'poll' => [
                        'id' => $poll->id,
                        'user_id' => $poll->user_id,
                        'question' => $poll->question,
                        'slug' => $poll->slug,
                        'expires_at' => $poll->expires_at?->toISOString(),
                        'created_at' => $poll->created_at->toISOString(),
                        'updated_at' => $poll->updated_at->toISOString(),
                        'is_expired' => $poll->isExpired(),
                        'total_votes' => $poll->total_votes,
                        'options_count' => $poll->options->count(),
                        'options' => $poll->options->map(function ($option) {
                            return [
                                'id' => $option->id,
                                'option_text' => $option->option_text,
                                'vote_count' => $option->vote_count,
                            ];
                        }),
                    ]
                ]
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Poll update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update poll. Please try again.',
                'errors' => ['general' => 'An error occurred while updating the poll.']
            ], 500);
        }
    }

    /**
     * Remove the specified poll from storage.
     */
    public function destroy(Poll $poll): JsonResponse
    {
        try {
            // Check if user owns the poll
            if ($poll->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this poll.',
                ], 403);
            }

            DB::beginTransaction();
            
            // Store poll data for response before deletion
            $pollData = [
                'id' => $poll->id,
                'question' => $poll->question,
                'slug' => $poll->slug,
            ];
            
            // Delete the poll (cascade will delete options and votes)
            $poll->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Poll deleted successfully!',
                'data' => [
                    'deleted_poll' => $pollData
                ]
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Poll deletion failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete poll. Please try again.',
                'errors' => ['general' => 'An error occurred while deleting the poll.']
            ], 500);
        }
    }

    /**
     * Submit a vote for a poll.
     */
    public function vote(Request $request, Poll $poll): JsonResponse
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'poll_option_id' => 'required|integer|exists:poll_options,id',
                'email' => 'required|email|max:255',
            ]);

            // Check if poll is expired
            if ($poll->isExpired()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This poll has expired and is no longer accepting votes.'
                ], 400);
            }

            // Verify the option belongs to this poll
            $pollOption = PollOption::where('id', $validated['poll_option_id'])
                ->where('poll_id', $poll->id)
                ->first();

            if (!$pollOption) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid poll option selected.'
                ], 400);
            }

            // Check if this email has already voted for this poll
            $existingVote = Vote::whereHas('pollOption', function ($query) use ($poll) {
                $query->where('poll_id', $poll->id);
            })->where('email', $validated['email'])->first();

            if ($existingVote) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already voted in this poll.'
                ], 400);
            }

            DB::beginTransaction();

            // Create the vote
            Vote::create([
                'poll_option_id' => $validated['poll_option_id'],
                'email' => $validated['email'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Your vote has been submitted successfully!'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Vote submission failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit vote. Please try again.'
            ], 500);
        }
    }
}