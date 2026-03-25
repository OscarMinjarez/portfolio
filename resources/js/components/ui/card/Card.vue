<script setup lang="ts">
import type { HTMLAttributes } from "vue"
import { cn } from "@/lib/utils"

const props = defineProps<{
  class?: HTMLAttributes["class"]
}>()
</script>

<template>
  <div
    data-slot="card"
    :class="
      cn(
        'rounded-xl border border-border/40 bg-transparent text-foreground shadow-none transition-all duration-300',
        props.class,
      )
    "
  >
    <slot />
  </div>
</template>
