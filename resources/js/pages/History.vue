<script setup>
import DashboardLayout from '../layouts/DashboardLayout.vue'
import { ref, onMounted } from 'vue'

const attendances = ref([])
// const apiUrl = import.meta.env.VITE_APP_API_URL;
const apiUrl = 'https://trial-production-147b.up.railway.app'

const fetchHistory = async () => {
  const token = localStorage.getItem('token')
  if (!token) return
  try {
    const res = await fetch(`${apiUrl}/api/attendance/history`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
      }
    })
    const data = await res.json()
    attendances.value = data.attendances.map((att, index) => ({ ...att, index: index + 1 }))
  } catch (err) {
    console.log(err)
  }
}

const fmt = (str) => {
  return new Date(str).toLocaleString('en-GB', {
    day: '2-digit', month: 'short', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
  })
}

onMounted(() => fetchHistory())
</script>

<template>
  <DashboardLayout>
    <div class="hist-wrap">
      <div class="hist-top">
        <h1 class="hist-title">Attendance History <span class="dot-live"></span></h1>
        <div class="hist-count">{{ attendances.length }} records</div>
      </div>

      <div class="hist-table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Check In</th>
              <th>Check Out</th>
              <th>Status</th>
              <th>Latitude</th>
              <th>Longitude</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in attendances" :key="item.id">
              <td class="td-num">{{ item.index }}</td>
              <td><span class="badge badge-in"><span class="dot dot-in"></span>{{ fmt(item.check_in_at) }}</span></td>
              <td>{{ item.check_out_at ? fmt(item.check_out_at) : '—' }}</td>
              <td>
                <span v-if="item.check_out_at" class="badge badge-out"><span class="dot dot-out"></span>Checked Out</span>
                <span v-else class="badge badge-pending"><span class="dot dot-pending"></span>Active</span>
              </td>
              <td class="coord">{{ item.latitude }}</td>
              <td class="coord">{{ item.longitude }}</td>
            </tr>
            <tr v-if="attendances.length === 0">
              <td colspan="6" class="no-data">No attendance records found</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </DashboardLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@400;600;700&display=swap');

.hist-wrap { font-family: 'Syne', sans-serif; padding: 2rem; max-width: 900px; margin: 0 auto; }
.hist-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
.hist-title { font-size: 24px; font-weight: 700; margin: 0; letter-spacing: -0.5px; }
.dot-live { display: inline-block; width: 8px; height: 8px; background: #22c55e; border-radius: 50%; margin-left: 8px; vertical-align: middle; }
.hist-count { font-size: 13px; color: #888; font-family: 'DM Mono', monospace; background: #f5f5f5; padding: 4px 12px; border-radius: 20px; border: 1px solid #eee; }
.hist-table-wrap { border: 1px solid #eee; border-radius: 12px; overflow: hidden; }
table { width: 100%; border-collapse: collapse; font-size: 14px; }
thead tr { background: #fafafa; border-bottom: 1px solid #eee; }
th { padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; color: #888; }
tbody tr { border-bottom: 1px solid #f0f0f0; transition: background 0.15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #fafafa; }
td { padding: 14px 16px; font-family: 'DM Mono', monospace; font-size: 13px;
background: white; }
.td-num { color: #bbb; font-size: 12px; }
.badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-family: 'Syne', sans-serif; }
.badge-in { background: #dcfce7; color: #166534; }
.badge-out { background: #fee2e2; color: #991b1b; }
.badge-pending { background: #fef9c3; color: #854d0e; }
.dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
.dot-in { background: #22c55e; }
.dot-out { background: #ef4444; }
.dot-pending { background: #eab308; }
.coord { color: #888; font-size: 12px; }
.no-data { text-align: center; padding: 40px; color: #aaa; font-family: 'Syne', sans-serif; }
</style>

























<!-- <script setup>
import DashboardLayout from '../layouts/DashboardLayout.vue'
import { ref, onMounted } from 'vue'
import { format } from 'date-fns'

const attendances = ref([])

const fetchHistory = async () => {
  const token = localStorage.getItem('token')
  if (!token) return

  try {
    const res = await fetch('http://127.0.0.1:8000/api/attendance/history', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
      }
    })

    const data = await res.json()
    attendances.value = data.attendances.map((att, index) => ({
      ...att,
      index: index + 1
    }))
  } catch (err) {
    console.log(err)
  }
}

const formatDate = (dateStr) => {
  return format(new Date(dateStr), 'yyyy-MM-dd HH:mm:ss')
}

onMounted(() => fetchHistory())
</script>

<template>
  <DashboardLayout>
    <div class="history-container">
      <h1>Attendance History</h1>

      <table class="history-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Latitude</th>
            <th>Longitude</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in attendances" :key="item.id">
            <td>{{ item.index }}</td>
            <td>{{ formatDate(item.check_in_at) }}</td>
            <td>{{ item.check_out_at ? formatDate(item.check_out_at) : '—' }}</td>
            <td>{{ item.latitude }}</td>
            <td>{{ item.longitude }}</td>
          </tr>
          <tr v-if="attendances.length === 0">
            <td colspan="5" class="no-data">No records found</td>
          </tr>
        </tbody>
      </table>
    </div>
  </DashboardLayout>
</template>

<style scoped>
.history-container {
  max-width: 800px;
  margin: 40px auto;
  padding: 20px;
}

.history-table {
  width: 100%;
  border-collapse: collapse;
}

.history-table th,
.history-table td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: center;
}

.history-table th {
  background-color: #4f46e5;
  color: white;
}

.history-table tr:nth-child(even) {
  background-color: #f9f9f9;
}

.no-data {
  text-align: center;
  font-style: italic;
  color: #555;
}
</style> -->