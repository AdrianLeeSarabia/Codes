const app = Vue.createApp({
  // Create functions, data
  data(){
    return{
      title: 'The Ledger',
      author: 'Adrian Lee Sarabia',
      age: 19
    }
  }
}
);

app.mount('#app')