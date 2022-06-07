<template>
  <div>
    login
  </div>
</template>

<script>
import request from "@/untils/request.js";

export default {
  created() {
    this.login()
  },
  methods: {
    login() {
      request.get("/api/login/login")
    }
  }

}
</script>

<style scoped>

</style>