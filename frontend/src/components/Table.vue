<template>
<div class="grid grid-cols-1 gap-1">
  <Card>
    <CardHeader>
      <CardTitle>Orders</CardTitle>
      <CardDescription>Search and browse pizza orders</CardDescription>
    </CardHeader>

    <CardContent class="space-y-4">
      <!-- Search & Date Filter -->
      <div class="flex gap-2 flex-wrap">
        <!-- Date Range Picker -->
        <DateRange @update:range="onRangeUpdate" />

        <!-- Search Input -->
        <div class="flex gap-2">
          <Input
            v-model="search"
            placeholder="Search pizza ID..."
            class="max-w-xs"
            @keyup.enter="fetchOrders(1)"
          />
          <Button @click="fetchOrders(1)">Search</Button>
        </div>
      </div>

      <!-- Orders Table -->
      <div class="overflow-auto border rounded-md">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>ID</TableHead>
              <TableHead>Date</TableHead>
              <TableHead>Time</TableHead>
              <TableHead>Items</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="order in orders" :key="order.id">
              <TableCell>{{ order.id }}</TableCell>
              <TableCell>{{ order.date }}</TableCell>
              <TableCell>{{ order.time }}</TableCell>
              <TableCell>
                {{ order.items.map(item => `${item.quantity} ${item.pizza.type.name}`).join(', ') }}
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>

      <!-- Pagination -->
      <div class="flex justify-between items-center pt-2">
        <Button
          variant="outline"
          size="sm"
          :disabled="currentPage === 1"
          @click="fetchOrders(currentPage - 1)"
        >
          Prev
        </Button>
        <span class="text-sm">Page {{ currentPage }} of {{ lastPage }}</span>
        <Button
          variant="outline"
          size="sm"
          :disabled="currentPage === lastPage"
          @click="fetchOrders(currentPage + 1)"
        >
          Next
        </Button>
      </div>
    </CardContent>
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
} from '@/components/ui/card'
import {
  Table,
  TableHeader,
  TableRow,
  TableHead,
  TableBody,
  TableCell,
} from '@/components/ui/table'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { getLocalTimeZone } from '@internationalized/date'
import DateRange from '@/components/DateRange.vue'
import type { DateRange as DateRangeType } from 'reka-ui'

// State
const selectedRange = ref<DateRangeType | null>(null)
const search = ref('')
const orders = ref([])
const currentPage = ref(1)
const lastPage = ref(1)

// Handle date range event from child component
function onRangeUpdate(range: DateRangeType) {
  selectedRange.value = range
  fetchOrders(1)
}

// Fetch orders from API
const fetchOrders = async (page = 1) => {

  const res = await axios.get(`${import.meta.env.VITE_BACKEND_API_URL}/orders`, {
    params: {
      page,
      search: search.value,
      start_date: selectedRange.value?.start ? selectedRange.value.start.toDate(getLocalTimeZone()).toISOString() : null,
      end_date: selectedRange.value?.end ? selectedRange.value.end.toDate(getLocalTimeZone()).toISOString() : null,
    },
  })

  orders.value = res.data.data
  currentPage.value = res.data.current_page
  lastPage.value = res.data.last_page
}

// Initial load
onMounted(() => {
  fetchOrders()
})
</script>
