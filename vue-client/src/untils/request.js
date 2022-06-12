import axios from 'axios'
import router from "@/router/index.js";

const request = axios.create({
    baseURL: 'http://www.bgmanag.io',  // 注意！！ 这里是全局统一加上了 后端接口前缀 前缀，后端必须进行跨域配置！
    timeout: 5000
})


// 请求白名单，如果请求在白名单里面，将不会被拦截校验权限
const whiteUrls = ["/login", '/register']


// request 拦截器
// 可以自请求发送前对请求做一些处理
// 比如统一加token，对请求参数统一加密
request.interceptors.request.use(config => {
    config.headers['Content-Type'] = 'application/json;charset=utf-8';

    let token = sessionStorage.getItem('token');
    if (!whiteUrls.includes(config.url)) {  // 校验请求白名单
        if (!token) {
            router.push("/login")
        } else {
            config.headers.token = token;  // 设置请求头
        }
    }
    return config
}, error => {
    return Promise.reject(error)
});

// response 拦截器
// 可以在接口响应后统一处理结果
request.interceptors.response.use(
    response => {
        let res = response.data;
        // 如果是返回的文件
        if (response.config.responseType === 'blob') {
            return res
        }
        // 兼容服务端返回的字符串数据
        if (typeof res === 'string') {
            res = res ? JSON.parse(res) : res
        }
        // 验证登录状态
        if (res.code === 250) {
            this.$message({
                type: "error",
                message: res.msg
            })
            router.push("/login")
        }
        return res;
    },
    error => {
        console.log('err' + error) // for debug
        return Promise.reject(error)
    }
)


export default request

