<template>
    <ul class="mb-0 tree-ul max-ht-1200">
        <taxonomy-item v-for="(taxonomy, index) in taxonomies"
                       :key="taxonomy.id"
                       :taxonomy-index="index"
                       :taxonomies-url="taxonomiesUrl"
                       :taxonomySingular="taxonomySingular"
                       :taxonomy="taxonomy"></taxonomy-item>
    </ul>
</template>

<script>
    export default {
        name: "taxonomies-list",

        props: ['taxonomies', 'taxonomiesUrl', 'taxonomySingular']
    }
</script>