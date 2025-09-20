<script setup lang="ts">
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { CheckCircle } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    messages: string[];
    title?: string;
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Success!',
});

const uniqueMessages = computed(() => Array.from(new Set(props.messages)));
</script>

<template>
    <Alert class="border-green-200 bg-green-50 text-green-800">
        <CheckCircle class="size-4 text-green-600" />
        <AlertTitle class="text-green-800">{{ title }}</AlertTitle>
        <AlertDescription>
            <ul class="list-inside list-disc text-sm text-green-700">
                <li v-for="(message, index) in uniqueMessages" :key="index">
                    {{ message }}
                </li>
            </ul>
        </AlertDescription>
    </Alert>
</template>
