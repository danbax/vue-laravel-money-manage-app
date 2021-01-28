<template>
  <div class="phone-viewport">
    <md-bottom-bar md-type="shift">
      <router-link class="navbar-brand" :to="{ name: 'home' }">
        <md-bottom-bar-item
          id="bottom-bar-item-home"
          md-label="Home"
          md-icon="home"
        ></md-bottom-bar-item>
      </router-link>
      <router-link class="navbar-brand" :to="{ name: 'add-record' }">
        <md-bottom-bar-item
          id="bottom-bar-item-pages"
          md-label="add"
          md-icon="add"
        ></md-bottom-bar-item>
      </router-link>
    </md-bottom-bar>
  </div>
</template>

<script>
export default {
  name: "RwvFooter"
};
</script>

<style lang="css" scoped>
.phone-viewport {
  overflow: hidden;
  position: fixed;
  bottom: 0;
  width: 100%;
  z-index: 100;
}
</style>
