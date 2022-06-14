<template>
  <div style="padding: 10px">
    <!--    功能区域-->
    <div style="margin: 10px 0">
      <el-button type="primary" @click="add">新增</el-button>
    </div>

    <!--    搜索区域-->
    <div style="margin: 10px 0">
      <el-input v-model="search" placeholder="请输入标题关键字" style="width: 20%" clearable></el-input>
      <el-button type="primary" style="margin-left: 5px" @click="load">查询</el-button>
    </div>
    <el-table
        v-loading="loading"
        :data="tableData"
        border
        stripe
        style="width: 100%">
      <el-table-column
          prop="id"
          label="ID"
          sortable
      >
      </el-table-column>
      <el-table-column
          prop="title"
          label="标题">
      </el-table-column>
      <el-table-column
          prop="author"
          label="作者">
      </el-table-column>
      <el-table-column
          prop="time"
          label="时间">
      </el-table-column>
      <el-table-column label="操作">
        <template #default="scope">
          <el-button size="mini" @click="details(scope.row)">详情</el-button>
          <el-button size="mini" @click="handleEdit(scope.row)">编辑</el-button>
          <el-popconfirm title="确定删除吗？" @confirm="handleDelete(scope.row.id)">
            <template #reference>
              <el-button size="mini" type="danger" style="margin-left: 10px">删除</el-button>
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
    </div>

    <el-dialog title="编辑文章" :visible.sync="dialogVisible" width="50%">
      <el-form :model="form" label-width="120px">
        <el-form-item label="标题">
          <el-input v-model="form.title" style="width: 50%"></el-input>
        </el-form-item>
        <el-form-item label="文本内容" prop="content" class="content"></el-form-item>
        <wangEditor v-bind:content="form.content" @listenToContent="getContent"/>
      </el-form>
      <template #footer>
          <span class="dialog-footer">
            <el-button @click="dialogVisible = false">取 消</el-button>
            <el-button type="primary" @click="save">确 定</el-button>
          </span>
      </template>
    </el-dialog>

    <el-dialog title="详情" :visible.sync="vis" width="50%">
      <span>标题</span>
      <el-card>
        <div v-html="detail.title" style="min-height: 20px"></div>
      </el-card>
      <el-divider></el-divider>
      <span>内容</span>
      <el-card>
        <div v-html="detail.content" style="min-height: 100px"></div>
      </el-card>
    </el-dialog>

  </div>
</template>

<style>body {
  margin: 0;
}</style>

<script>

import request from "../untils/request";
import row from "element-ui/packages/row";
import wangEditor from '../components/wangEditor.vue'

export default {
  name: 'News',
  components: {wangEditor},
  data() {
    return {
      form: {},
      dialogVisible: false,
      search: '',
      currentPage: 1,
      pageSize: 10,
      total: 0,
      tableData: [],
      vis: false,
      detail: {},
      loading: true,
      contentCache: ''
    }
  },
  created() {
    this.load()
  },
  mounted() {       //所有元素加载完成之后最后加载该方法内的模块
  },
  methods: {
    details(row) {
      this.detail = row;
      this.vis = true;
    },
    load() {
      request.get("/api/news/index", {
        params: {
          pageNum: this.currentPage,
          pageSize: this.pageSize,
          search: this.search
        }
      }).then(res => {
        this.tableData = res.records
        this.total = res.conunt
      })
      this.loading = false
    },
    add() {
      this.form = {}
      this.dialogVisible = true
    },
    getContent(val) {
      this.contentCache = val;
    },
    save() {
      if (this.form.id) {  // 更新
        this.form.content = this.contentCache
        request.put("/api/news/update", this.form).then(res => {
          console.log(res)
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
      } else {    //新增
        this.form.content = this.contentCache
        request.put("/api/news/save", this.form).then(res => {
          console.log(res)
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
      // console.log(this.form)
      this.dialogVisible = true
    },
    handleDelete(id) {
      request.post("/api/news/delete", id).then(res => {
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
    handleSizeChange(pageSize) {
      this.pageSize = pageSize
      this.load()
    },
    handleCurrentChange(pageNum) {
      this.currentPage = pageNum
      this.load()
    }
  }
}
</script>
