<template>
  <div class="home">
    <img alt="Vue logo" src="../assets/logo.png">
    <ApiStatus />
  </div>
</template>

<script>
import ApiStatus from "../components/ApiStatus";

export default {
  name: 'Home',
  components: {
    ApiStatus
  }
}
</script>
