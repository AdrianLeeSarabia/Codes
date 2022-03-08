const books = Vue.createApp({
  // Create functions, data
  data(){
    return {
      showBooks: true,
      btitle: 'The Ledger',
      bauthor: 'Adrian Lee Sarabia',
      year: 2008
    }
  },
  methods:{
    toggleShowBooks(){
      this.showBooks = !this.showBooks
    }
  }
});
books.mount('#books')