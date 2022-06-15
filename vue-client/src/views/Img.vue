<template>
  <div style="padding: 10px">
    <div style="margin: 10px">
      <el-button type="primary" @click="add">上传新图片</el-button>
    </div>
    <div class="position" style="width: 150px ; padding-left: 30px ; font-weight: bold ; color: dodgerblue ">图片管理</div>
    <div class="images">
      <div v-for="(item, index) in info" :key="index" class="image-middle">
        <el-card shadow="hover" :body-style="{ padding: '10px' }">
          <el-popover>
            <el-image :src="info[index].img_url" slot="reference" class="image"/>
            <el-image :src="info[index].img_url" class="imagePreview"/>
          </el-popover>
          <div style="text-align:center;padding-top:12px">
            <span>
              {{ info[index].name }}
              <el-popconfirm title="确定删除吗？" @confirm="handleDelete(info[index].id) ">
               <template #reference>
                <el-button size="mini" type="danger" style="margin-left: 7px">删除</el-button>
              </template>
             </el-popconfirm>
            </span>
          </div>
        </el-card>
      </div>

      <el-dialog title="上传新图片" :visible.sync="dialogVisible" width="30%">
        <el-form :model="form" label-width="120px">
          <el-form-item label="名称">
            <el-input v-model="form.name" style="width: 80%"></el-input>
          </el-form-item>
          <el-form-item label="图片">
            <el-upload ref="upload" :headers="getToken" action="http://www.bgmanag.io/api/img/upload"
                       :on-success="filesUploadSuccess">
              <el-button type="primary">点击上传</el-button>
            </el-upload>
          </el-form-item>
        </el-form>
        <template #footer>
          <span class="dialog-footer">
            <el-button @click="dialogVisible = false">取 消</el-button>
            <el-button type="primary" @click="save">确 定</el-button>
          </span>
        </template>
      </el-dialog>

    </div>
  </div>
</template>

<style scoped>
/* “Tissue Search”字体样式 */
.position {
  margin-left: 10px;
  font-size: 30px;
  font-weight: 510;
  margin-top: 25px;
}

/* 图片总布局，样式 */
.images {
  display: flex;
  margin-top: 20px;
  margin-left: 21px;
  margin-right: 20px;
  flex-wrap: wrap;
}

/* 图片之间 */
.image-middle {
  margin-right: 15px;
  margin-bottom: 15px;
}

/* 单张图片样式 */
.image {
  width: 200px;
  height: 190px;
}
</style>

<script>
import request from "@/untils/request.js";

export default {
  name: "Img",
  data() {
    return {
      info: [],
      form: {},
      dialogVisible: false,
      getToken: {
        token: sessionStorage.getItem("token"),
      },
      url: {}
    }
  },
  created() {
    this.load()
  },
  mounted() {
  },
  methods: {
    load() {
      request.get("/api/img/index").then(res => {
        this.info = res.records
      })
      this.loading = false
    },
    add() {
      this.form = {}
      this.dialogVisible = true
      this.$nextTick(() => {
        this.$refs['upload'].clearFiles()  // 清除历史文件列表
      })
    },
    filesUploadSuccess(res) {
      this.url = res.data
      this.form.img_url = this.url
    },
    save() {
      request.post("/api/img/create", this.form).then(res => {
        if (res.code === 1) {
          this.$message({
            type: "success",
            message: "新增成功"
          })
          this.dialogVisible = false
          this.load()
        } else {
          this.$message({
            type: "error",
            message: res.msg
          })
        }
      })
    },
    handleDelete(id) {
      request.post("/api/img/delete", id).then(res => {
        if (res.code === 1) {
          this.$message({
            type: "success",
            message: "删除成功"
          })
        } else {
          this.$message({
            type: "error",
            message: '服务器异常'
          })
        }
        this.load()
      })
    },
  }
}
</script>

