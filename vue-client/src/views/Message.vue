<template>
  <div style="margin-top: 10px; margin-bottom: 80px">
    <el-card>
      <div style="padding: 20px; color: #888">
        <div>
          <el-input type="textarea" :rows="3" v-model="entity.content"></el-input>
          <div style="text-align: right; padding: 10px">
            <el-button type="primary" @click="save">留言</el-button>
          </div>
        </div>
      </div>

      <div style="display: flex; padding: 20px" v-for="item in messages">
        <div style="text-align: center; flex: 1">
          <el-image :src="item.avatar" style="width: 60px; height: 60px; border-radius: 50%"></el-image>
        </div>
        <div style="padding: 0 10px; flex: 5">
          <div><b style="font-size: 14px">{{ item.nick_name }}</b></div>
          <div style="padding: 10px 0; color: #888">
            {{ item.content }}
          </div>
          <div style="color: #888; font-size: 12px">
            <span>{{ item.time }}</span>
            <el-button type="text" style="margin-left: 20px" @click="reReply(item.id)">回复</el-button>
            <el-button type="text" size="mini" @click="del(item.id)" v-if="item.nick_name === user.nick_name">删除
            </el-button>
          </div>
        </div>
      </div>

    </el-card>


  </div>
</template>

<script>
import request from "@/untils/request";

export default {
  name: "Message",
  data() {
    return {
      user: {},
      messages: [],
      entity: {}
    }
  },
  created() {
    this.user = sessionStorage.getItem("user") ? JSON.parse(sessionStorage.getItem("user")) : {};
    this.loadMessage();
  },
  methods: {
    loadMessage() {
      request.get("api/message/index").then(res => {
        this.messages = res.item;
      })
    },
    save() {
      if (!this.entity.content) {
        this.$message({
          message: "请填写内容",
          type: "warning"
        });
        return;
      }
      // 如果是评论的话，在 save的时候要注意设置 当前模块的id为 foreignId。也就是  entity.foreignId = 模块id
      request.post("/api/message/save", this.entity).then(res => {
        if (res.code === 1) {
          this.$message({
            message: "评论成功",
            type: "success"
          });
        } else {
          this.$message({
            message: '服务器错误',
            type: "error"
          });
        }
        this.entity = {}
        this.loadMessage();
      })
    },
    del(id) {
      request.post("/api/message/delete", id).then(res => {
        this.$message({
          message: "删除成功",
          type: "success"
        });
        this.loadMessage()
      })
    }
  }
}
</script>