<script setup>
import DashboardLayout from '../layouts/DashboardLayout.vue'
import { ref, onMounted } from 'vue'

const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December']
const currentDate = ref(new Date())
const calendarData = ref({})
const tooltip = ref('Hover a date for details')

const apiUrl = import.meta.env.VITE_API_URL;

const fetchCalendar = async (month, year) => {
  const token = localStorage.getItem('token')
  if (!token) return {}
  try {
    const res = await fetch(`${apiUrl}/api/attendance/calendar`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({ month, year })
    })
    const data = await res.json()
    return data.calendar || {}
  } catch { return {} }
}

const loadCalendar = async () => {
  const month = currentDate.value.getMonth() + 1
  const year = currentDate.value.getFullYear()
  calendarData.value = await fetchCalendar(month, year)
}

const prevMonth = () => {
  const d = new Date(currentDate.value)
  d.setMonth(d.getMonth() - 1)
  currentDate.value = d
  loadCalendar()
}

const nextMonth = () => {
  const d = new Date(currentDate.value)
  d.setMonth(d.getMonth() + 1)
  currentDate.value = d
  loadCalendar()
}

const getDays = () => {
  const month = currentDate.value.getMonth()
  const year = currentDate.value.getFullYear()
  const firstDay = new Date(year, month, 1).getDay()
  const daysInMonth = new Date(year, month + 1, 0).getDate()
  const today = new Date()
  const days = []

  for (let i = 0; i < firstDay; i++) days.push({ empty: true })

  for (let d = 1; d <= daysInMonth; d++) {
    const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`
    const dayData = calendarData.value[dateStr]
    const isFuture = new Date(year, month, d) > today
    const isToday = today.getMonth() === month && today.getFullYear() === year && today.getDate() === d

    let status = 'absent'
    if (isFuture) status = 'future'
    else if (dayData?.status === 'present') status = 'present'
    else if (dayData?.status === 'partial') status = 'partial'

    days.push({ d, dateStr, status, isToday, hours: dayData?.total_hours || null })
  }
  return days
}

const monthLabel = () => `${MONTHS[currentDate.value.getMonth()]} ${currentDate.value.getFullYear()}`

onMounted(loadCalendar)
</script>














<template>
  <DashboardLayout>
    <div class="cal-wrap">

      <div class="cal-header">
        <button @click="prevMonth">&#8592;</button>
        <h2>{{ monthLabel() }}</h2>
        <button @click="nextMonth">&#8594;</button>
      </div>

      <div class="legend">
        <span class="dot green"></span> Present (5h+)
        <span class="dot blue"></span> Partial (&lt;5h)
        <span class="dot red"></span> Absent
      </div>

      <div class="grid-days">
        <div v-for="d in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']" :key="d">{{ d }}</div>
      </div>

      <div class="grid-cells">
        <div v-for="(day, i) in getDays()" :key="i"
          :class="['cell', day.empty ? 'empty' : day.status, day.isToday ? 'today' : '']"
          @mouseenter="tooltip = day.dateStr ? (day.hours ? `${day.dateStr} — ${day.hours.toFixed(2)} hrs` : `${day.dateStr} — No check-in`) : ''">
          <template v-if="!day.empty">
            {{ day.d }}
            <span v-if="day.hours" class="hours">{{ day.hours.toFixed(1) }}h</span>
          </template>
        </div>
      </div>

      <p class="tooltip">{{ tooltip }}</p>
    </div>
  </DashboardLayout>
</template>












<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@400;600;700&display=swap');


.cal-wrap { max-width: 480px; margin: auto; padding: 24px; background-color: white; border: 1px solid #eee; border-radius: 25px; }
.cal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.cal-header h2 { font-size: 18px; font-weight: 500; margin: 0; }
button { background: none; border: 1px solid #ddd; border-radius: 6px; padding: 6px 14px; cursor: pointer; }
.legend { display: flex; align-items: center; gap: 12px; font-size: 13px; color: #666; margin-bottom: 16px; flex-wrap: wrap; }
.dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
.dot.green { background: #639922; }
.dot.blue { background: #378ADD; }
.dot.red { background: #E24B4A; }
.grid-days { display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; font-size: 12px; color: #999; margin-bottom: 4px; }
.grid-cells { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }
.cell { aspect-ratio: 1; border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 13px; font-weight: 500; }
.cell.empty { background: none; }
.cell.present { background: #EAF3DE; color: #27500A; }
.cell.partial { background: #E6F1FB; color: #0C447C; }
.cell.absent { background: #FCEBEB; color: #791F1F; }
.cell.future { color: #ccc; }
.cell.today { outline: 2px solid #4f46e5; }
.hours { font-size: 9px; font-weight: 400; opacity: 0.8; }
.tooltip { text-align: center; font-size: 12px; color: #999; margin-top: 12px; min-height: 18px; }
</style>