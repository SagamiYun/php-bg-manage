<template>
  <div style="border: 1px solid #ccc;">
    <Toolbar
        style="border-bottom: 1px solid #ccc"
        :editor="editor"
        :defaultConfig="toolbarConfig"
        :mode="mode"
    />
    <Editor
        style="height: 500px; overflow-y: hidden;"
        v-model="html"
        :defaultConfig="editorConfig"
        :mode="mode"
        @onCreated="onCreated"
        @onChange="onChange"
    />
  </div>
</template>

<script>
import Vue from 'vue'
import {Editor, Toolbar} from '@wangeditor/editor-for-vue'

export default Vue.extend({
  props: {
    content: {}
  },
  components: {Editor, Toolbar},
  watch: { // watch 监听 props 中的值
    content(newVal, oldVal) { // newVal 为新值， oldVal 为旧值
      this.contents = newVal
      console.log(this.contents)
    }
  },
  data() {
    return {
      editor: null,
      html: '',
      toolbarConfig: {},
      editorConfig: {MENU_CONF: {}, placeholder: '请输入文章内容...', excludeKeys: []},
      mode: '', // or 'simple'
      contents: this.content
    }
  },
  created() {
    this.contents = this.content
    console.log(this.content)
    this.load()
  },
  methods: {
    load() {
      console.log(this.content)
      this.html = this.content
      this.editorConfig.MENU_CONF['uploadImage'] = {
        fieldName: 'file',
        maxFileSize: 2 * 1024 * 1024, // 2M
        maxNumberOfFiles: 1,
        allowedFileTypes: ['image/*'],
        headers: {
          token: sessionStorage.getItem('token'),
        },
        server: 'http://www.bgmanag.io/api/img/upload',
        timeout: 5 * 1000, // 5 秒
      }
    },
    onCreated(editor) {
      this.editor = Object.seal(editor) // 一定要用 Object.seal() ，否则会报错
    },
    onChange(editor) {
      setTimeout(() => {
        this.$emit('listenToContent', editor.getHtml());
      });
    }
  },
  mounted() {
    this.contents = this.content
    console.log(this.contents)
  },
  beforeDestroy() {
    const editor = this.editor
    if (editor == null) return
    editor.destroy() // 组件销毁时，及时销毁编辑器
  }
})
</script>

<style src="@wangeditor/editor/dist/css/style.css"></style>


