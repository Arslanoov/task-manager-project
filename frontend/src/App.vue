<template>
  <div id="app">
    <header id="header">
      <div id="nav">
        <Nav/>
      </div>
    </header>
    <main id="main">
      <div id="aside">
        <Sidebar/>
      </div>
      <div class="container page">
        <router-view/>
      </div>
    </main>
    <footer id="footer">
      <div class="container">
        <hr />
        <p>&copy; App. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<script>
  import Nav from './components/Nav';
  import Sidebar from './components/Sidebar';

  export default {
    components: {
      Nav,
      Sidebar,
    }
  }
</script>

<style lang="scss">
  @import "/assets/variables/bootstrap";
  @import "/assets/variables/main";
  @import "~bootstrap/dist/css/bootstrap.css";
  @import "~bootstrap-vue/dist/bootstrap-vue.css";
  @import "~@fortawesome/fontawesome-free/css/all.css";
  @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700&display=swap');

  #app {
    font-family: $font;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #34373d;
  }

  button:focus, button:active {
    outline: none !important;
    box-shadow: none !important;
    border: 0 !important;
  }

  #header {
    width: 100%;

    a {
      font-weight: bold;
      color: #2c3e50;

      &.router-link-exact-active {
        color: #42b983;
      }
    }
  }

  .page {
    margin-top: 20px;
  }

  #footer {
    position: fixed;
    width: 100%;
    height: 40px;
    bottom: 30px;
    text-align: center;
  }
</style>
