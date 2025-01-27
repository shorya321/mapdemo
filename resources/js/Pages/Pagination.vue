<template>
    <div class="pagination">
      <button
        :disabled="currentPage === 1"
        @click="goToPage(currentPage - 1)"
        class="pagination-button"
      >
        Previous
      </button>
      
      <span>{{ currentPage }} of {{ lastPage }}</span>
      
      <button
        :disabled="currentPage === lastPage"
        @click="goToPage(currentPage + 1)"
        class="pagination-button"
      >
        Next
      </button>
    </div>
  </template>
  
  <script setup>
  const props = defineProps({
    currentPage: Number,
    lastPage: Number
  })
  
  const emit = defineEmits(['page-changed'])
  
  const goToPage = (page) => {
    if (page >= 1 && page <= props.lastPage) {
      emit('page-changed', page)
    }
  }
  </script>
  
  <style scoped>
  .pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }
  
  .pagination-button {
    padding: 5px 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  .pagination-button:disabled {
    background-color: #cccccc;
    cursor: not-allowed;
  }
  </style>
  