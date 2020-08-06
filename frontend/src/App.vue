<template>
  <div id="app">
    <div id="nav">
      <router-link to="/">Home</router-link> |
      <router-link to="/about">About</router-link> |
      <template v-if="$store.getters.isLoggedIn">
        <li class="nav-item"><a class="nav-link" @click="logout" href="">Log Out</a></li>
      </template>
      <template v-else>
        <li class="nav-item"><router-link class="nav-link" :to="{name: 'login'}">Log In</router-link></li>
        <li class="nav-item"><router-link class="nav-link" :to="{name: 'signup'}">Sign Up</router-link></li>
      </template>
    </div>
    <router-view/>
  </div>
</template>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav {
  padding: 30px;

  a {
    font-weight: bold;
    color: #2c3e50;

    &.router-link-exact-active {
      color: #42b983;
    }
  }
}
</style>
