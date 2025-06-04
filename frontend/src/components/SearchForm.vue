<template>
  <div class="search-form">
    <div class="form-group">
      <label for="searchName"><strong>Compliance Search Name:</strong></label>
      <input v-model="searchName" id="searchName" type="text" placeholder="Enter search name" />
    </div>
    <div class="form-group">
      <label for="searchQuery"><strong>Search Query:</strong></label>
      <input v-model="searchQuery" id="searchQuery" type="text" placeholder="Enter KQL query" />
    </div>

    <div class="buttons">
      <button @click="connect">Connect</button>
      <button @click="createSearch">Create Search</button>
      <button @click="startSearch">Start Search</button>
    </div>
    <div class="buttons">
      <button @click="getResults">Get Results</button>
      <button @click="purgeEmails">Purge Emails</button>
      <button @click="checkStatus">Check Status</button>
    </div>

    <div class="output-wrapper">
      <textarea readonly rows="10" v-model="outputText"></textarea>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'SearchForm',
  data() {
    return {
      searchName: '',
      searchQuery: '',
      outputText: ''
    };
  },
  methods: {
    appendOutput(text) {
      this.outputText += text + '\n';
      this.$nextTick(() => {
        const el = this.$el.querySelector('textarea');
        el.scrollTop = el.scrollHeight;
      });
    },

    async connect() {
      this.appendOutput('Connecting to Security & Compliance Center...');
      try {
        const res = await axios.post('http://localhost/backend/public/api/connect');
        if (res.data.success) this.appendOutput(res.data.output);
        else this.appendOutput('[Error] ' + JSON.stringify(res.data));
      } catch (err) {
        this.appendOutput('[Error] ' + err.message);
      }
    },

    async createSearch() {
      this.appendOutput(`Creating Compliance Search: ${this.searchName}`);
      try {
        const res = await axios.post('http://localhost/backend/public/api/search/create', {
          name: this.searchName,
          query: this.searchQuery
        });
        if (res.data.success) this.appendOutput(res.data.output);
        else this.appendOutput('[Error] ' + JSON.stringify(res.data));
      } catch (err) {
        this.appendOutput('[Error] ' + err.message);
      }
    },

    async startSearch() {
      this.appendOutput(`Starting Compliance Search: ${this.searchName}`);
      try {
        const res = await axios.post('http://localhost/backend/public/api/search/start', {
          name: this.searchName
        });
        if (res.data.success) this.appendOutput(res.data.output);
        else this.appendOutput('[Error] ' + JSON.stringify(res.data));
      } catch (err) {
        this.appendOutput('[Error] ' + err.message);
      }
    },

    async getResults() {
      this.appendOutput(`Fetching Compliance Search results for: ${this.searchName}`);
      try {
        const res = await axios.get(`http://localhost/backend/public/api/search/results/${encodeURIComponent(this.searchName)}`);
        if (res.data.success) this.appendOutput(res.data.output);
        else this.appendOutput('[Error] ' + JSON.stringify(res.data));
      } catch (err) {
        this.appendOutput('[Error] ' + err.message);
      }
    },

    async purgeEmails() {
      this.appendOutput(`Purging emails from search: ${this.searchName}`);
      try {
        const res = await axios.post('http://localhost/backend/public/api/search/purge', {
          name: this.searchName
        });
        if (res.data.success) this.appendOutput(res.data.output);
        else this.appendOutput('[Error] ' + JSON.stringify(res.data));
      } catch (err) {
        this.appendOutput('[Error] ' + err.message);
      }
    },

    async checkStatus() {
      const purgeIdentity = this.searchName + '_Purge';
      this.appendOutput(`Checking purge status for: ${purgeIdentity}`);
      try {
        const res = await axios.get(`http://localhost/backend/public/api/search/status/${encodeURIComponent(purgeIdentity)}`);
        if (res.data.success) this.appendOutput(res.data.output);
        else this.appendOutput('[Error] ' + JSON.stringify(res.data));
      } catch (err) {
        this.appendOutput('[Error] ' + err.message);
      }
    }
  }
};
</script>

<style scoped>
.search-form {
  max-width: 700px;
  margin: 0 auto;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group input {
  width: 100%;
  padding: 0.5rem;
  font-size: 1rem;
  box-sizing: border-box;
}

.buttons {
  margin-bottom: 0.5rem;
}

button {
  padding: 0.5rem 1rem;
  margin-right: 0.5rem;
  font-size: 1rem;
  cursor: pointer;
}

.output-wrapper textarea {
  width: 100%;
  font-family: monospace;
  padding: 0.5rem;
  box-sizing: border-box;
}
</style>
