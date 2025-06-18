<template>
  <div class="grid grid-cols-1 gap-1">
    <Card class="w-full">
      <CardHeader>
        <CardTitle>Top Selling Pizzas</CardTitle>
        <CardDescription>Based on total quantity ordered</CardDescription>
      </CardHeader>
  
      <CardContent class="h-[300px] flex items-center justify-center">
        <Pie :data="chartData" :options="chartOptions" :key="chartKey" />
      </CardContent>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import {
  Chart as ChartJS,
  ArcElement,
  Tooltip,
  Legend
} from 'chart.js'
import { Pie } from 'vue-chartjs'
import {
  Card,
  CardHeader,
  CardTitle,
  CardDescription,
  CardContent,
} from '@/components/ui/card'

ChartJS.register(ArcElement, Tooltip, Legend)

// Reactive chart data with initial structure
const chartData = ref({
  labels: [],
  datasets: [
    {
      label: 'Orders',
      data: [],
      backgroundColor: ['#f87171', '#34d399', '#60a5fa', '#fbbf24', '#c084fc'],
      borderWidth: 1,
    },
  ],
})

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom',
    },
  },
}

const chartKey = ref(0)

const fetchTopPizzas = async () => {
  try {
    const res = await axios.get(`${import.meta.env.VITE_BACKEND_API_URL}/order-details-top-pizzas`)
    const data = res.data

    chartData.value.labels = data.map(item => item.pizza_name)
    chartData.value.datasets[0].data = data.map(item => parseInt(item.total_quantity))

    chartKey.value++ // trigger re-render
  } catch (err) {
    console.error('Error fetching chart data:', err)
  }
}

onMounted(fetchTopPizzas)
</script>
