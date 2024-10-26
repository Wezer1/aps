<template>
    <div>
        <table border="1px">
            <tr>
                <td>title</td>
                <td>content</td>
                <td>Action</td>

            </tr >
            <tr v-for="post in posts" v-bind:key="post.id">
                <td>{{ post.title }}</td>
                <td>{{ post.content }}</td>
                <td><button v-on:click="deletePost(post.id)">Delete</button></td>
            </tr>
        </table>
    </div>
</template>

<script>
import Vue from 'vue'
import axios from "axios";
import VueAxios from 'vue-axios'

Vue.use(VueAxios, axios)
export default {
    name: 'Delete',

    data() {
        return {
            posts: null
        }
    },
    methods:
        {
            getData(){
                this.axios.get('/api/posts').then(result=>{
                    this.posts=result.data.data
                })
            },
            deletePost(id){
                this.axios.delete('api/posts/'+id).then(result =>{
                    this.getData();
                    }
                )
            }

        },
    mounted() {
        this.getData()
    }

}
</script>

<style scoped>

</style>
