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
import { ref, onMounted } from 'vue'
import axios from 'axios'
import {
  Card,
  CardHeader,
  CardTitle,
  CardDescription,
  CardContent,
  CardFooter
} from '@/components/ui/card'

const cards = ref([
  {
    title: 'Total Orders',
    description: 'All',
    content: '',
  },
  {
    title: 'Revenue',
    description: 'Gross sales',
    content: '',
  },
  {
    title: 'Total Pizza Sold',
    description: 'Total Quantity',
    content: '',
  },
  {
    title: 'Top Product',
    description: 'Best seller',
    content: '',
  },
])

const fetchDashboardMetrics = async () => {
  try {
    const res = await axios.get(`${import.meta.env.VITE_BACKEND_API_URL}/dashboard-metrics`)
    const data = res.data

    cards.value[0].content = data.total_orders.toLocaleString()
    cards.value[1].content = `$${Number(data.revenue).toLocaleString()}`
    cards.value[2].content = data.total_quantity.toLocaleString()
    cards.value[3].content = data.top_pizza
  } catch (error) {
    console.error('Failed to fetch dashboard metrics', error)
  }
}

onMounted(fetchDashboardMetrics)

</script>
