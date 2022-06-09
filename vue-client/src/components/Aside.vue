<template>
  <div>
    <el-menu
        style="width: 200px; min-height: calc(100vh - 50px)"
        :default-active="$route.path"
        router
    >
      <el-menu-item :index="m.path" v-for="m in itemData " :key="m.id">
        <i :class="m.icon"></i> {{ m.comment }}
      </el-menu-item>
    </el-menu>
  </div>
</template>


<script>
// import request from "@/utils/request";

export default {
  name: "Aside",
  created() {
    // console.log(this.$route.path)
    // let userStr = sessionStorage.getItem("user") || "{}"
    // this.user = JSON.parse(userStr)
    let item = sessionStorage.getItem('permissions');
    this.itemData = JSON.parse(item);

    // let token = sessionStorage.getItem('token');
    // if (!token) {
    //   return
    // }

  },
  data() {
    return {
      itemData: {}
    }
  },
  methods: {

    // matchState(string = "") {
    //   return string !== "/person" && string !== "/password";
    // }
  }
}
</script>

<style scoped>

</style>