<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)
const router = useRouter()

const login = async () => {
  error.value = ''
  loading.value = true
  try {
    const res = await fetch('http://localhost:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value,
      }),
    })

    const data = await res.json()

    if (!res.ok) {
      throw new Error(data.message || 'Login failed!')
    }

    localStorage.setItem('token', data.token)
    router.push('/attendance')
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

const goToRegister = () => {
  router.push('/register')
}

onMounted(() => {
  const token = localStorage.getItem('token')
  if (token) router.push('/attendance')
})
</script>

<template>
  <div class="page">
    <div class="card">
      <div class="brand">
        <div class="brand-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round">
            <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
          </svg>
        </div>
        <span class="brand-name">AttendanceApp</span>
      </div>

      <h1 class="card-title">Welcome back</h1>
      <p class="card-sub">Sign in to your account to continue</p>

      <div v-if="error" class="error-msg">{{ error }}</div>

      <div class="field">
        <label>Email</label>
        <input v-model="email" type="email" placeholder="you@example.com" />
      </div>

      <div class="field">
        <label>Password</label>
        <input v-model="password" type="password" placeholder="••••••••" />
      </div>

      <button class="btn-login" @click="login" :disabled="loading">
        {{ loading ? 'Signing in...' : 'Sign In' }}
      </button>

      <div class="divider">
        <div class="divider-line"></div>
        <span>or</span>
        <div class="divider-line"></div>
      </div>

      <div class="register-row">
        Don't have an account? <a @click="goToRegister">Create one</a>
      </div>
    </div>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@400;600;700;800&display=swap');

.page { min-height: 100vh; width: 100%; display: flex; align-items: center; justify-content: center; padding: 2rem; background: #f5f5f5; }
.card { width: 100%; max-width: 420px; background: white; border: 1px solid #eee; border-radius: 20px; 
  padding: 2.5rem;
  overflow: visible; 
 }
.brand { display: flex; align-items: center; gap: 10px; margin-bottom: 2rem; }
.brand-icon { width: 36px; height: 36px; background: #4f46e5; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.brand-icon svg { width: 18px; height: 18px; }
.brand-name { font-size: 16px; font-weight: 700; letter-spacing: -0.3px; }
.card-title { font-size: 26px; font-weight: 800; letter-spacing: -0.8px; margin-bottom: 6px; }
.card-sub { font-size: 14px; color: #888; margin-bottom: 2rem; }
.field { margin-bottom: 1rem; }
.field label { display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.6px; color: #888; margin-bottom: 6px; }
.field input { width: 100%; padding: 11px 14px; border: 1px solid #eee; border-radius: 10px; font-size: 14px; font-family: 'DM Mono', monospace; background: #fafafa; outline: none; transition: border-color 0.2s; box-sizing: border-box; }
.field input:focus { border-color: #4f46e5; background: white; }
.btn-login { width: 100%; padding: 12px; background: #4f46e5; color: white; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; margin-top: 0.5rem; transition: background 0.2s; display: block; }
.btn-login:hover { background: #4338ca; }
.btn-login:disabled { opacity: 0.7; cursor: not-allowed; }
.error-msg { background: #fee2e2; color: #991b1b; font-size: 13px; padding: 10px 14px; border-radius: 8px; margin-bottom: 1rem; font-family: 'DM Mono', monospace; }
.divider { display: flex; align-items: center; gap: 12px; margin: 1.5rem 0; }
.divider-line { flex: 1; height: 1px; background: #eee; }
.divider span { font-size: 12px; color: #aaa; }
.register-row { text-align: center; font-size: 14px; color: #888; }
.register-row a { color: #4f46e5; font-weight: 600; cursor: pointer; }
.register-row a:hover { text-decoration: underline; }
</style>
































<!-- <script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { onMounted } from 'vue';

const email = ref('');
const password = ref('');
const router = useRouter();

const login = async () => {
  try {
    const res = await fetch('http://localhost:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value,
      }),
    });

    const data = await res.json();

    if (!res.ok) {
      throw new Error(data.message || 'Login failed!');
    }

    localStorage.setItem('token', data.token);
    alert('Logged in successfully!');
    router.push('/attendance');
  } catch (err) {
    alert(err.message);
  }
};

// Navigate to Register page
const goToRegister = () => {
  router.push('/register');
};


onMounted(() => {
  const token = localStorage.getItem('token');
  if (token) {
    router.push('/attendance'); 
  }
});



</script>


<template>
  <div class="login-container">
    <h2>Login</h2>
    <form @submit.prevent="login">
      <input v-model="email" type="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Password" />
      <button type="submit">Login</button>
    </form>

    <p class="register-text">
      Don't have an account?
      <button @click="goToRegister" class="register-btn">Register</button>
    </p>

  </div>
</template>



<style scoped>
.login-container {
  max-width: 400px;
  margin: 100px auto;
  padding: 2rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 0 10px #ccc;
  text-align: center;
}

.login-container h2 {
  margin-bottom: 1.5rem;
}

.login-container input {
  width: 100%;
  padding: 0.6rem;
  margin-bottom: 1rem;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.login-container button {
  width: 100%;
  padding: 0.6rem;
  background-color: #4f46e5;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.login-container button:hover {
  background-color: #4338ca;
}

.register-text {
  margin-top: 1rem;
  font-size: 0.9rem;
}

.register-btn {
  background: none;
  border: none;
  color: #4f46e5;
  cursor: pointer;
  text-decoration: underline;
  font-weight: bold;
  margin-left: 5px;
}

.register-btn:hover {
  color: #4338ca;
}




</style>



 -->