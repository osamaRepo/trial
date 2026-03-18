<!-- <template>
  <div class="layout">

    <aside class="sidebar">
      <h2 class="logo">Attendance</h2>

      <ul>
        <li @click="goToDashboard">CheckInOut</li>
        <li @click="goToHistory">History</li>
        <li @click="goToCalender">Calender</li>
      </ul>
    </aside>

    <div class="main">

     
      <header class="navbar">
        <h3>Attendance System</h3>
        <button @click="logout">Logout</button>
      </header>

    
      <div class="content">
        <slot></slot>
      </div>

    </div>

  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()


const logout = async () => {
  try {
    const token = localStorage.getItem('token');
    if (!token) return router.push('/');
    const res = await fetch('http://127.0.0.1:8000/api/logout', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      },
    });

    if (!res.ok) {
      const data = await res.json();
      throw new Error(data.message || 'Logout failed');
    }

    localStorage.removeItem('token');
    alert('Logged out successfully!');
    router.push('/');

  } catch (err) {
    alert(err.message);
  }
};

const goToDashboard = () => {
  router.push('/attendance')
}

const goToHistory = () => {
  router.push('/history')
}

const goToCalender = () => {
  router.push('/calender')
}

</script>

<style scoped>
.layout{
  display:flex;
  height:100vh;
  font-family: Arial, Helvetica, sans-serif;
}

.sidebar{
  width:220px;
  background:#4f46e5;
  color:white;
  padding:20px;
}

.logo{
  margin-bottom:30px;
}

.sidebar ul{
  list-style:none;
  padding:0;
}

.sidebar li{
  padding:10px 0;
  cursor:pointer;
}

.sidebar li:hover{
  background:rgba(255,255,255,0.2);
  padding-left:10px;
}

.main{
  flex:1;
  display:flex;
  flex-direction:column;
}

.navbar{
  height:60px;
  background:#f3f4f6;
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:0 20px;
  border-bottom:1px solid #ddd;
}

.navbar button{
  background:#ef4444;
  color:white;
  border:none;
  padding:8px 15px;
  border-radius:4px;
  cursor:pointer;
}

.navbar button:hover{
  background:#dc2626;
}

.content{
  padding:30px;
}
</style>
 -->




<template>
  <div class="layout">

    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-brand">
        <div class="brand-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round">
            <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
          </svg>
        </div>
        <span class="brand-name">AttendanceApp</span>
      </div>

      <nav class="sidebar-nav">
        <div
          class="nav-item"
          :class="{ active: currentRoute === '/attendance' }"
          @click="goToDashboard"
        >
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
            <circle cx="12" cy="9" r="2.5"/>
          </svg>
          Check In/Out
        </div>

        <div
          class="nav-item"
          :class="{ active: currentRoute === '/history' }"
          @click="goToHistory"
        >
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
          </svg>
          History
        </div>

        <div
          class="nav-item"
          :class="{ active: currentRoute === '/calender' }"
          @click="goToCalender"
        >
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
          Calendar
        </div>
      </nav>

      <div class="sidebar-footer">
        <div class="nav-item logout-item" @click="logout">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
          Logout
        </div>
      </div>
    </aside>

    <div class="main">
      <!-- Navbar -->
      <header class="navbar">
        <div class="navbar-left">
          <h3 class="navbar-title">{{ pageTitle }}</h3>
        </div>
        <div class="navbar-right">
          <div class="user-pill">
            <div class="user-avatar">U</div>
          </div>
        </div>
      </header>

      <!-- Content -->
      <div class="content">
        <slot></slot>
      </div>
    </div>

  </div>
</template>

<script setup>
import { useRouter, useRoute } from 'vue-router'
import { computed } from 'vue'

const router = useRouter()
const route = useRoute()
// const apiUrl = import.meta.env.VITE_APP_API_URL;
const apiUrl = 'https://trial-production-147b.up.railway.app'


const currentRoute = computed(() => route.path)

const pageTitle = computed(() => {
  if (route.path === '/attendance') return 'Check In / Out'
  if (route.path === '/history') return 'Attendance History'
  if (route.path === '/calender') return 'Calendar'
  return 'Dashboard'
})

const logout = async () => {
  try {
    const token = localStorage.getItem('token')
    if (!token) return router.push('/')
    await fetch(`${apiUrl}/api/logout`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      },
    })
    localStorage.removeItem('token')
    router.push('/')
  } catch (err) {
    localStorage.removeItem('token')
    router.push('/')
  }
}

const goToDashboard = () => router.push('/attendance')
const goToHistory = () => router.push('/history')
const goToCalender = () => router.push('/calender')
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@400;600;700;800&display=swap');

.layout { display: flex; height: 100vh; 
  /* font-family: 'Syne', sans-serif; */
   background: #f5f5f5; }

/* Sidebar */
.sidebar { width: 240px; background: white; border-right: 1px solid #eee; display: flex; flex-direction: column; padding: 1.5rem 1rem; }
.sidebar-brand { display: flex; align-items: center; gap: 10px; margin-bottom: 2rem; padding: 0 0.5rem; }
.brand-icon { width: 32px; height: 32px; background: #4f46e5; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.brand-icon svg { width: 16px; height: 16px; }
.brand-name { font-size: 15px; font-weight: 700; letter-spacing: -0.3px; color: #1a1a1a; }
.sidebar-nav { flex: 1; display: flex; flex-direction: column; gap: 4px; }
.nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 600; color: #888; transition: all 0.15s; }
.nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }
.nav-item:hover { background: #f5f5f5; color: #1a1a1a; }
.nav-item.active { background: #eef2ff; color: #4f46e5; }
.sidebar-footer { border-top: 1px solid #eee; padding-top: 1rem; }
.logout-item { color: #ef4444; }
.logout-item:hover { background: #fee2e2; color: #ef4444; }

/* Navbar */
.main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
.navbar { height: 60px; background: white; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; padding: 0 24px; flex-shrink: 0; }
.navbar-title { font-size: 16px; font-weight: 700; color: #1a1a1a; letter-spacing: -0.3px; }
.user-avatar { width: 32px; height: 32px; background: #eef2ff; color: #4f46e5; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; }

/* Content */
.content { flex: 1; overflow-y: auto; padding: 2rem; }
</style>