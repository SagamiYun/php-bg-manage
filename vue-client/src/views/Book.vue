<template>
  <div style="padding: 10px">
    <!--    功能区域-->
    <div style="margin: 10px 0">
      <el-button type="primary" @click="add">新增</el-button>
      <el-popconfirm
          title="确定删除吗？"
          @confirm="deleteBatch"
      >
        <template #reference>
          <el-button type="danger" style="margin-left: 10px">批量删除</el-button>
        </template>
      </el-popconfirm>
    </div>

    <!--    搜索区域-->
    <div style="margin: 10px 0">
      <el-input v-model="search" placeholder="请输入关键字" style="width: 20%" clearable></el-input>
      <el-button type="primary" style="margin-left: 5px" @click="load">查询</el-button>
    </div>
    <el-table
        v-loading="loading"
        :data="tableData"
        border
        stripe
        s style="width: 100%"
        @selection-change="handleSelectionChange"
    >
      <el-table-column
          type="selection"
          width="55">
      </el-table-column>
      <el-table-column
          prop="id"
          label="ID"
          sortable
      >
      </el-table-column>
      <el-table-column
          prop="name"
          label="名称">
      </el-table-column>
      <el-table-column
          prop="author"
          label="作者">
      </el-table-column>
      <el-table-column
          prop="create_time"
          label="出版时间">
      </el-table-column>
      <el-table-column
          label="封面">
        <template #default="scope">
          <el-image
              style="width: 100px; height: 100px"
              :src="scope.row.cover"
              :preview-src-list="[scope.row.cover]">
          </el-image>
        </template>
      </el-table-column>
      <el-table-column label="操作" width="240">
        <template #default="scope">
          <el-button size="mini" @click="handleEdit(scope.row)">编辑</el-button>
          <el-popconfirm title="确定删除吗？" @confirm="handleDelete(scope.row.id) ">
            <template #reference>
              <el-button size="mini" type="danger" style="margin-left: 7px">删除</el-button>
            </template>
          </el-popconfirm>
        </template>
      </el-table-column>
    </el-table>

    <div style="margin: 10px 0">
      <el-pagination
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
          :current-page="currentPage"
          :page-sizes="[5, 10, 20]"
          :page-size="pageSize"
          layout="total, sizes, prev, pager, next, jumper"
          :total="total">
      </el-pagination>

      <el-dialog title="提示" :visible.sync="dialogVisible" width="30%">
        <el-form :model="form" label-width="120px">
          <el-form-item label="名称">
            <el-input v-model="form.name" style="width: 80%"></el-input>
          </el-form-item>
          <el-form-item label="作者">
            <el-input v-model="form.author" style="width: 80%"></el-input>
          </el-form-item>
          <el-form-item label="出版时间">
            <el-date-picker v-model="form.create_time" type="date" style="width: 80%" clearable></el-date-picker>
          </el-form-item>
          <el-form-item label="封面">
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

<style>
body {
  margin: 0;
}
</style>


<script>


import request from "@/untils/request";

export default {

  name: 'Book',
  components: {},
  data() {
    return {
      user: {},
      form: {},
      dialogVisible: false,
      search: '',
      currentPage: 1,
      pageSize: 10,
      total: 0,
      tableData: [],
      loading: true,
      ids: [],
      getToken: {
        token: sessionStorage.getItem("token"),
      },
    }
  },
  created() {
    this.load()
  },
  methods: {
    deleteBatch() {
      if (!this.ids.length) {
        this.$message.warning("请选择数据！")
        return
      }
      this.ids.id = this.ids
      request.post("/api/book/deleteBatch", this.ids).then(res => {
        if (res.code === 1) {
          this.$message.success("批量删除成功")
          this.load()
        } else {
          this.$message.error('服务器错误')
        }
      })
    },
    handleSelectionChange(val) {
      this.ids = val.map(v => v.id)       // [{id,name}, {id,name}] => [id,id]
    },
    filesUploadSuccess(res) {
      console.log(res)
      this.form.cover = res.data
    },
    load() {
      request.get("/api/book/index", {
        params: {
          pageNum: this.currentPage,
          pageSize: this.pageSize,
          search: this.search
        }
      }).then(res => {
        this.tableData = res.records
        this.total = res.conunt
        this.loading = false
      })
    },
    add() {
      this.form = {}
      this.dialogVisible = true
      this.$nextTick(() => {
        this.$refs['upload'].clearFiles()  // 清除历史文件列表
      }) // 清除历史文件列表
    },
    save() {
      if (this.form.id) {  // 更新
        request.put("/api/book/update", this.form).then(res => {
          if (res.code === 1) {
            this.$message({
              type: "success",
              message: "更新成功"
            })
            this.dialogVisible = false
            this.load()
          } else {
            this.$message({
              type: "error",
              message: '服务器错误'
            })
          }
        })
      } else {  // 新增
        console.log(this.form)
        request.post("/api/book/create", this.form).then(res => {
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
      }
    },
    handleEdit(row) {
      this.form = JSON.parse(JSON.stringify(row))
      this.dialogVisible = true
      this.$nextTick(() => {
        this.$refs['upload'].clearFiles()  // 清除历史文件列表
      })
    },
    handleDelete(id) {
      request.post("/api/book/delete", id).then(res => {
        if (res.code === 1) {
          this.$message({
            type: "success",
            message: "删除成功"
          })
        } else {
          this.$message({
            type: "error",
            message: '服务器错误'
          })
        }
        this.load()
      })
    },
    handleSizeChange(pageSize) {   // 改变当前每页的个数触发
      this.pageSize = pageSize
      this.load()
    },
    handleCurrentChange(pageNum) {  // 改变当前页码触发
      this.currentPage = pageNum
      this.load()
    }
  }
}
</script>
