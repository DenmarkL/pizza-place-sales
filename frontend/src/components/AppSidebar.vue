<template>
  <Sidebar>
    <SidebarContent>
      <SidebarHeader>
        <div class="flex items-center gap-2 ml-5 mr-5 text-lg font-semibold px-4 py-2">
          <Pizza class="w-5 h-5 text-red-600" />
          <span>Pizza Place</span>
        </div>   
      </SidebarHeader>

      <SidebarGroup>
        <SidebarGroupLabel>
          Menu
        </SidebarGroupLabel>
        <SidebarGroupContent>
          <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
              <SidebarMenuButton asChild>
                <a :href="item.url" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-[oklch(var(--sidebar-primary))] hover:text-[oklch(var(--sidebar-primary-foreground))]">
                  <component :is="item.icon" class="w-4 h-4" />
                  <span>{{ item.title }}</span>
                </a>
              </SidebarMenuButton>
            </SidebarMenuItem>
          </SidebarMenu>
        </SidebarGroupContent>
      </SidebarGroup>
    </SidebarContent>
  </Sidebar>
</template>

<script setup lang="ts">
import { Calendar, Home, Inbox, Search, Settings, Pizza } from "lucide-vue-next"
import {
  Sidebar,
  SidebarHeader,
  SidebarContent,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from "@/components/ui/sidebar"

// Menu items.
const items = [
  {
    title: "Dashboard",
    url: "/index",
    icon: Home,
  },
];
</script>
