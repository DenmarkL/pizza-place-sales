<template>
    <div class="grid grid-cols-4 gap-4">
      <Card
        v-for="(item, index) in cards"
        :key="index"
        class="w-full h-full flex flex-col justify-between"
      >
        <CardHeader>
          <CardTitle>{{ item.title }}</CardTitle>
          <CardDescription>{{ item.description }}</CardDescription>
        </CardHeader>
        <CardContent class="flex-1">
          {{ item.content }}
        </CardContent>
        <CardFooter>
          {{ item.footer }}
        </CardFooter>
      </Card>
    </div>
</template>



<script setup lang="ts">
import { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter } from '@/components/ui/card'

const cards = [
  {
    title: 'Total Orders',
    description: 'This month',
    content: '1,284',
    footer: 'Last updated 1 hour ago',
  },
  {
    title: 'Revenue',
    description: 'Gross sales',
    content: '$12,540',
    footer: 'Compared to last month',
  },
  {
    title: 'New Customers',
    description: 'Registered users',
    content: '432',
    footer: '+12% growth',
  },
  {
    title: 'Top Product',
    description: 'Best seller',
    content: 'Pepperoni Pizza',
    footer: '420 orders',
  },
]
</script>
