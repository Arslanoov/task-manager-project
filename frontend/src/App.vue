<template>
  <div id="app">
    <div class="container">
      <header id="header">
        <div id="nav">
          <Nav/>
        </div>
      </header>
      <main id="main">
        <router-view/>
      </main>
    </div>
  </div>
</template>

<script>
  import Nav from './components/Nav';

  export default {
    components: {
      Nav
    }
  }
</script>

<style lang="scss">
  @import "~bootstrap/dist/css/bootstrap.css";
  @import "~bootstrap-vue/dist/bootstrap-vue.css";

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
