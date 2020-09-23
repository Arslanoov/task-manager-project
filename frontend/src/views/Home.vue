<template>
  <div class="main-page">
    <p data-test="welcome">Welcome to Furious TODO!</p>
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
