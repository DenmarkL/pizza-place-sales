<template>
  <Card class="w-full">
    <CardHeader>
      <CardTitle>Top Selling Pizzas</CardTitle>
      <CardDescription>Based on total orders</CardDescription>
    </CardHeader>

    <CardContent class="h-[300px] flex items-center justify-center">
      <Pie :data="chartData" :options="chartOptions" />
    </CardContent>
  </Card>
</template>

<script setup lang="ts">
import {
  Chart as ChartJS,
  Tooltip,
  Legend,
  ArcElement,
} from 'chart.js'
import {
  Card,
  CardHeader,
  CardTitle,
  CardDescription,
  CardContent,
  CardFooter
} from '@/components/ui/card'
import { Pie } from 'vue-chartjs'

ChartJS.register(ArcElement, Tooltip, Legend)

// Sample pizza data
const chartData = {
  labels: ['Pepperoni', 'Hawaiian', 'Veggie', 'Margherita', 'Meat Lovers'],
  datasets: [
    {
      label: 'Orders',
      data: [320, 200, 150, 180, 100],
      backgroundColor: [
        '#f87171', // red
        '#34d399', // green
        '#60a5fa', // blue
        '#fbbf24', // yellow
        '#c084fc', // purple
      ],
      borderWidth: 1,
    },
  ],
}

const chartOptions = {
  responsive: true,
  maintainAspectRatio: true,
  plugins: {
    legend: {
      position: 'bottom',
    },
  },
}
</script>
