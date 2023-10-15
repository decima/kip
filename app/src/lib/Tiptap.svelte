<script>
    import {onDestroy, onMount} from 'svelte'
    import {Editor} from '@tiptap/core'
    import StarterKit from '@tiptap/starter-kit'
    import {Markdown} from 'tiptap-markdown';
    import {Image as ImageExt}  from "@tiptap/extension-image";

    let element
    let editor

    export let path
    export let content
    export let editable = true

    export let uploader = async function(file){
        console.log(file);
    }
    onMount(() => {

        ImageExt.configure({
            inline: true,
            inlineUploading: true,
            inlineContent: true,
        });

        editor = new Editor({
            element: element,
            extensions: [
                StarterKit,
                Markdown,
                ImageExt,
            ],
            content,
            editable,
            autofocus: true,
            onTransaction: () => {
                // force re-render so `editor.isActive` works as expected
                editor = editor
            },
            onUpdate: () => {
                content = editor.storage.markdown.getMarkdown()

            },
            editorProps: {
                handleDrop: function (view, event, slice, moved) {
                    if (!moved && event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files[0]) { // if dropping external files
                        console.log(event.dataTransfer.files);
                        for (let i = 0; i < event.dataTransfer.files.length; i++) {
                            let file = event.dataTransfer.files[i];
                            uploadFromDataTransfer(file,view,event, slice, moved);
                        }
                        return true; // handled
                    }
                    return false; // not handled use default behaviour
                }
            },
        })
    })

    const uploadFromDataTransfer = (file,view, event, slice, moved) => {
        let filesize = ((file.size / 1024) / 1024).toFixed(4); // get the filesize in MB
        if ((file.type === "image/jpeg" || file.type === "image/png") && filesize < 10) { // check valid image type under 10MB
            // check the dimensions
            let _URL = window.URL || window.webkitURL;
            let img = new Image(); /* global Image */
            img.src = _URL.createObjectURL(file);
            img.onload = function () {
                if (this.width > 5000 || this.height > 5000) {
                    window.alert("Your images need to be less than 5000 pixels in height and width."); // display alert
                } else {
                    // valid image so upload to server
                    // uploadImage will be your function to upload the image to the server or s3 bucket somewhere
                    uploader(file).then(function (response) {
                        const { schema } = view.state;
                        console.log(response);
                        const coordinates = view.posAtCoords({ left: event.clientX, top: event.clientY });
                        console.log("pass1");
                        const node = schema.nodes.image.create({ src: response.path }); // creates the image element
                        console.log("pass2");
                        const transaction = view.state.tr.insert(coordinates.pos, node); // places it in the correct position
                        console.log("pass3")
                        return view.dispatch(transaction);

                    }).catch(function (error) {
                        if (error) {
                            window.alert("There was a problem uploading your image, please try again.");
                        }
                    });
                }
            };
        } else {
            window.alert("Images need to be in jpg or png format and less than 10mb in size.");
        }
    }



    onDestroy(() => {
        if (editor) {
            editor.destroy()
        }
    })

    const onStyleChange = (e) => {
        const val = e.target.value
        switch (val) {
            case 'h1':
            case 'h2':
            case 'h3':
            case 'h4':
                const level = parseInt(val.replace('h', ''))
                editor.chain().focus().setHeading({level: level}).run()
                break;

            case 'p':
                editor.chain().focus().setParagraph().run()
                break;
            case 'code':
                editor.chain().focus().toggleCodeBlock().run()
                break;
        }
    }
</script>
<div class="box box-border p-2 h-full ">

    {#if editor && editable}
        <div class="navbar bg-base-100 mx-2 sticky ">

            <div class="join">
                <select class=" select select-bordered select-xs join-item" on:change={onStyleChange}>
                    <option value="h1" selected={editor.isActive('heading', { level: 1 })}>H1</option>
                    <option value="h2" selected={editor.isActive('heading', { level: 2 })}>H2</option>
                    <option value="h3" selected={editor.isActive('heading', { level: 3 })}>H3</option>
                    <option value="h4" selected={editor.isActive('heading', { level: 4 })}>H4</option>
                    <option value="p" selected={editor.isActive('paragraph')}>Paragraph</option>
                    <option value="code" selected={editor.isActive('codeBlock')}>Code</option>
                </select>

                <button class="btn btn-xs join-item" on:click={() => editor.chain().focus().toggleCodeBlock().run()}
                        class:active={editor.isActive('codeBlock')}>
                    &lt;&gt;
                </button>
            </div>

        </div>
    {/if}

    <div class="editor prose prose-xs max-w-none box-border

" bind:this={element}/>
</div>
<style>
    button.active {
        background: black;
        color: white;
    }

    .box {
        @apply box-border;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        @apply flex flex-col;

    }

    .editor {
        overflow: auto;
        @apply h-full;


    }

    :global([contenteditable]) {
        @apply min-h-full;
    }

    :global([contenteditable]:focus) {
        @apply outline-none;
    }
</style>