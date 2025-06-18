
<template>
<div class="px-4 py-6">
    <div>
        <Cards/>
    </div>
    <div class="mt-5">
        <Charts />
    </div>
</div>
</template>

<script setup lang="ts">
import { SidebarProvider, SidebarTrigger } from "@/components/ui/sidebar";
import AppSidebar from "@/components/AppSidebar.vue";
import Cards from "@/components/Card.vue";
import Charts from "@/components/TopPizzasChart.vue";
</script>