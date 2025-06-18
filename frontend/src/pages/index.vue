
<template>
<div class="px-4 py-6">
    <div>
        <Cards/>
    </div>
    <div class="mt-5">
        <Charts />
    </div>
    <div class="mt-5">
        <Table @update:range="onDateRangeChange" />
    </div>
</div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import type { DateRange } from 'reka-ui'
import { SidebarProvider, SidebarTrigger } from "@/components/ui/sidebar";
import AppSidebar from "@/components/AppSidebar.vue";
import Cards from "@/components/Card.vue";
import Charts from "@/components/TopPizzasChart.vue";
import Table from "@/components/Table.vue";

const selectedRange = ref<DateRange | null>(null)

function onDateRangeChange(range: DateRange) {
  selectedRange.value = range
  console.log('Selected Range:', range)
}

</script>