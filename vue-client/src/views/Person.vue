<template>
  <div>
    <el-card style="width: 40%; margin: 10px">
      <el-form ref="form" :model="form" label-width="80px">
        <el-form-item style="text-align: center" label-width="0">
          <el-upload
              class="avatar-uploader"
              action="upload"
              :http-request="uploadFile"
              :show-file-list="false"
              :on-success="uploadFile"
          >

            <img v-if="form.avatar" :src="form.avatar" class="avatar">
            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
          </el-upload>
        </el-form-item>
        <el-form-item label="用户名">
          <el-input v-model="form.username" disabled></el-input>
        </el-form-item>
        <el-form-item label="昵称">
          <el-input v-model="form.nick_name"></el-input>
        </el-form-item>
        <el-form-item label="年龄">
          <el-input v-model="form.age"></el-input>
        </el-form-item>
        <el-form-item label="性别">
          <el-input v-model="form.sex"></el-input>
        </el-form-item>
        <el-form-item label="地址">
          <el-input v-model="form.address"></el-input>
        </el-form-item>
      </el-form>
      <div style="text-align: center">
        <el-button type="primary" @click="update">保存</el-button>
      </div>
    </el-card>

  </div>
</template>

<script>
import request from "@/untils/request";

export default {
  name: "Person",
  data() {
    return {
      form: {}
    }
  },
  created() {
    let str = sessionStorage.getItem("user") || "{}"
    this.form = JSON.parse(str)
    this.load()
  },
  methods: {
    load() {
      request.get("/api/admin/updateInfo").then(res => {
        sessionStorage.setItem('user', JSON.stringify(res.user))
        let str = sessionStorage.getItem("user") || "{}"
        this.form = JSON.parse(str)
        this.$emit("userInfo")
      })
    },

    uploadFile(params) {
      let file = params.file
      let imageType = file.type
      let isImage = true;
      if (imageType !== 'image/jpeg' && imageType !== 'image/jpg' && imageType !== 'image/png') {
        let isImage = false
        if (!isImage) {
          this.$message.error("上传头像图片只能是 JPG/PNG/JPEG 格式")
          return
        }
      }
      let isLt2M = file.size / 1024 / 1024 < 2
      if (!isLt2M) {
        this.$message.error("图片最大不能超过2M")
        return
      }
      let formData = new FormData()
      formData.append('file', file)
      request.post('/api/admin/uploadimg', formData).then(res => {
        if (res.code === 0) {
          this.$message({
            type: "error",
            message: res.msg
          })
        } else {
          this.$message({
            type: "success",
            message: res.msg
          })
          this.form.avatar = res.imgurl
        }
      })
    },
    update() {
      request.put("/api/admin/update", this.form).then(res => {
        if (res.code === 1) {
          this.$message({
            type: "success",
            message: "更新成功"
          })
          sessionStorage.setItem("user", JSON.stringify(this.form))
          // 触发Layout更新用户信息
          this.$emit("userInfo")
        } else {
          this.$message({
            type: "error",
            message: '服务器错误'
          })
        }
      })
    }
  }
}
</script>

<style>
.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.avatar-uploader .el-upload:hover {
  border-color: #409EFF;
}

.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 120px;
  height: 120px;
  line-height: 120px;
  text-align: center;
}

.avatar {
  width: 178px;
  height: 178px;
  display: block;
}
</style>
