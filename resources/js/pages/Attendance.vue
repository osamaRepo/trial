<script setup>
import DashboardLayout from '../layouts/DashboardLayout.vue'
import { ref, onMounted } from 'vue'

const message = ref('')
const status = ref('loading')
const loading = ref(false)

// const apiUrl = import.meta.env.VITE_APP_API_URL;
const apiUrl = 'https://trial-production-147b.up.railway.app'

const getStatus = async () => {
  const token = localStorage.getItem('token')
  if (!token) return

  try {
    const res = await fetch(`${apiUrl}/api/attendance/history`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`,
      },
    })
    const data = await res.json()
    const today = new Date().toISOString().split('T')[0]
    const todaySessions = data.attendances.filter(a => a.check_in_at.startsWith(today))
    const openSession = todaySessions.find(a => a.check_out_at === null)
    status.value = openSession ? 'checkout' : 'checkin'
  } catch (err) {
    message.value = 'Failed to fetch attendance status.'
  }
}

onMounted(() => getStatus())

const checkInOut = () => {
  const token = localStorage.getItem('token')
  if (!token) { message.value = 'You must be logged in.'; return }
  if (!navigator.geolocation) { message.value = 'Geolocation is not supported.'; return }

  loading.value = true

  navigator.geolocation.getCurrentPosition(async (position) => {
    try {
      const res = await fetch(`${apiUrl}/api/attendance/check`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify({
          latitude: position.coords.latitude,
          longitude: position.coords.longitude
        })
      })
      const data = await res.json()
      if (!res.ok) throw new Error(data.message || 'Check-in/out failed')
      message.value = data.message
      await getStatus()
    } catch (err) {
      message.value = err.message
    } finally {
      loading.value = false
    }
  }, () => {
    message.value = 'Location access denied.'
    loading.value = false
  })
}
</script>

<template>
  <DashboardLayout>
    <div class="page">
      <div class="card">

        <div class="brand">
          <div class="brand-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
              <circle cx="12" cy="9" r="2.5"/>
            </svg>
          </div>
          <span class="brand-name">AttendanceApp</span>
        </div>

        <h1 class="card-title">Mark Attendance</h1>
        <p class="card-sub">{{ new Date().toLocaleDateString('en-GB', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</p>

        <div v-if="message" :class="['msg', status === 'checkout' ? 'msg-success' : 'msg-info']">
          {{ message }}
        </div>

        <div class="status-badge" v-if="status !== 'loading'">
          <span :class="['badge', status === 'checkout' ? 'badge-in' : 'badge-out']">
            <span class="dot"></span>
            {{ status === 'checkout' ? 'Currently Checked In' : 'Not Checked In' }}
          </span>
        </div>

        <button
          v-if="status === 'checkin'"
          class="btn btn-checkin"
          @click="checkInOut"
          :disabled="loading"
        >
          {{ loading ? 'Getting location...' : 'Check In' }}
        </button>

        <button
          v-else-if="status === 'checkout'"
          class="btn btn-checkout"
          @click="checkInOut"
          :disabled="loading"
        >
          {{ loading ? 'Processing...' : 'Check Out' }}
        </button>

        <button v-else class="btn" disabled>Loading...</button>

      </div>
    </div>
  </DashboardLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@400;600;700;800&display=swap');

.page { width: 100%; display: flex; align-items: center; justify-content: center; padding: 2rem; 
  /* font-family: 'Syne', sans-serif; */
 }
.card { width: 100%; max-width: 420px; background: white; border: 1px solid #eee; border-radius: 20px; padding: 2.5rem; }
.brand { display: flex; align-items: center; gap: 10px; margin-bottom: 2rem; }
.brand-icon { width: 36px; height: 36px; background: #4f46e5; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.brand-icon svg { width: 18px; height: 18px; }
.brand-name { font-size: 16px; font-weight: 700; letter-spacing: -0.3px; }
.card-title { font-size: 26px; font-weight: 800; letter-spacing: -0.8px; margin-bottom: 6px; }
.card-sub { font-size: 14px; color: #888; margin-bottom: 2rem; font-family: 'DM Mono', monospace; }
.status-badge { margin-bottom: 1.5rem; }
.badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; }
.badge-in { background: #dcfce7; color: #166534; }
.badge-out { background: #fee2e2; color: #991b1b; }
.dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
.msg { font-size: 13px; padding: 10px 14px; border-radius: 8px; margin-bottom: 1rem; font-family: 'DM Mono', monospace; }
.msg-success { background: #dcfce7; color: #166534; }
.msg-info { background: #e0e7ff; color: #3730a3; }
.btn { width: 100%; padding: 13px; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; 
  /* font-family: 'Syne', sans-serif; */
   cursor: pointer; transition: background 0.2s, transform 0.1s; display: block; box-sizing: border-box; }
.btn:active { transform: scale(0.98); }
.btn:disabled { opacity: 0.6; cursor: not-allowed; }
.btn-checkin { background: #4f46e5; color: white; }
.btn-checkin:hover:not(:disabled) { background: #4338ca; }
.btn-checkout { background: #ef4444; color: white; }
.btn-checkout:hover:not(:disabled) { background: #dc2626; }
</style>






























<!-- 

<script setup>
import DashboardLayout from '../layouts/DashboardLayout.vue'
import { ref, onMounted } from 'vue'

const message = ref('')
const status = ref('loading') // 'checkin' or 'checkout'
const loading = ref(false)

// Get today's attendance status
const getStatus = async () => {
  const token = localStorage.getItem('token')
  if (!token) return

  try {
    const res = await fetch('http://127.0.0.1:8000/api/attendance/history', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`,
      },
    })
    const data = await res.json()

    // Get today's date in YYYY-MM-DD format
    const today = new Date().toISOString().split('T')[0]

    // Filter today's sessions
    const todaySessions = data.attendances.filter(a =>
      a.check_in_at.startsWith(today)
    )

    // Check if there is an open session (checked in but not yet checked out)
    const openSession = todaySessions.find(a => a.check_out_at === null)

    if (openSession) {
      status.value = 'checkout' // show Check Out
    } else {
      status.value = 'checkin'  // show Check In
    }

  } catch (err) {
    console.log(err)
    message.value = 'Failed to fetch attendance status.'
  }
}

// Call on page load
onMounted(() => getStatus())


const checkInOut = () => {
  const token = localStorage.getItem('token')
  if (!token) {
    message.value = 'You must be logged in.'
    return
  }

  if (!navigator.geolocation) {
    message.value = "Geolocation is not supported."
    return
  }

  loading.value = true  //

  navigator.geolocation.getCurrentPosition(async (position) => {
    const latitude = position.coords.latitude
    const longitude = position.coords.longitude

    try {
      const res = await fetch('http://127.0.0.1:8000/api/attendance/check', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify({ latitude, longitude })
      })

      const data = await res.json()
      if (!res.ok) throw new Error(data.message || 'Check-in/out failed')

      message.value = data.message

      // Refresh status after action
      await getStatus()

    } catch (err) {
      message.value = err.message
    } finally {
      loading.value = false  // stop loading
    }

  }, () => {
    message.value = "Location access denied."
    loading.value = false
  })
}







</script>

<template>
  <DashboardLayout>
    <div class="attendance-box">
      <h1>Attendance</h1>
      <p v-if="message">{{ message }}</p>

   

      <button v-if="status==='checkin'" @click="checkInOut" :disabled="loading">
        {{ loading ? 'Loading...' : 'Check In' }}
      </button>
      <button v-else-if="status==='checkout'" @click="checkInOut" :disabled="loading">
        {{ loading ? 'Loading...' : 'Check Out' }}
      </button>
      <button v-else disabled>Loading...</button>
    </div>
  </DashboardLayout>




</template>

<style scoped>
.attendance-box {
  max-width: 400px;
  margin: auto;
  padding: 30px;
  text-align: center;
  border: 1px solid #ddd;
  border-radius: 8px;
}

button {
  padding: 10px 20px;
  background: #4f46e5;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 10px;
}

button:hover {
  background: #4338ca;
}

button:disabled {
  background: #ccc;
  cursor: not-allowed;
}
</style> -->