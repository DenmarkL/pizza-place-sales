<template>
  <SidebarProvider>
    <AppSidebar/>
    <main class="w-full">
      <SidebarTrigger />
      <RouterView />
    </main>
  </SidebarProvider>
</template>

<script setup lang="ts">
  import { ref, onMounted } from 'vue'
  import AppSidebar from '@/components/AppSidebar.vue'
  import { SidebarProvider, SidebarTrigger } from '@/components/ui/sidebar'
</script>