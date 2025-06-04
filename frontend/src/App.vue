<template>
  <div id="app">
    <h1>Security & Compliance Center GUI</h1>
    <SearchForm />
  </div>
</template>

<script>
import SearchForm from './components/SearchForm.vue';

export default {
  name: 'App',
  components: { SearchForm }
};
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  margin: 2rem;
}

h1 {
  margin-bottom: 1rem;
}
</style>
