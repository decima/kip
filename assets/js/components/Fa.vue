<template>
    <i :class="[type, `fa-${icon}`]" @click="$emit('click')"></i>
</template>

<script>
    export default {
        name : "Icon",
        props : {
            icon : {
                type : String
            },
            type : {
                type : String,
                default : 'fal',
                validator: function (value) {
                    return ['fal', 'fad', 'fas', 'far', 'fab'].indexOf(value) > -1
                }
            }
        }
    }
</script>