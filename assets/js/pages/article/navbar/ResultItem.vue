<template>
    <div class="result-preview">
        <div>{{ result.webpath }}</div>
        <div v-html="querySnippet" class="query-snippet"></div>
    </div>
</template>

<script>
    export default {
        name : "ResultItem",
        props : {
            result : {},
            query : {}
        },
        computed : {
            querySnippet(){
                const startOffset = 50;
                const endOffset = 50;
                const queryPosition = this.result.content.toLowerCase().indexOf(this.query);
                const startIndex = queryPosition - startOffset < 0 ? 0 : queryPosition - startOffset;
                const endIndex = queryPosition + endOffset;
                let querySnippet = this.result.content.slice(startIndex, endIndex).toLowerCase()

                let highlightedSnippet = "";
                let index = 0;
                let remainingSnippet = querySnippet;

                while(remainingSnippet.length > 0){
                    const indexOfQueryInSnippet = remainingSnippet.indexOf(this.query);
                    highlightedSnippet += remainingSnippet.substring(0, indexOfQueryInSnippet > -1 ? indexOfQueryInSnippet : remainingSnippet.length);
                    if(indexOfQueryInSnippet > -1){
                        highlightedSnippet += `<span class='query-highlight'>${ this.query }</span>`;
                    }
                    index += indexOfQueryInSnippet + this.query.length;
                    remainingSnippet = remainingSnippet.slice(index, remainingSnippet.length -1);
                }

                return highlightedSnippet;
            }
        }
    }
</script>

<style scoped lang="less">
    .query-snippet {
        font-size: 12px;
        color: @text-color-secondary;
    }

</style>

<style lang="less">

    .result-preview {
        .query-highlight {
            display: inline-block;
            background-color : @yellow-2;
            border-radius: 2px;
            padding: 0 2px;
        }
    }

</style>
