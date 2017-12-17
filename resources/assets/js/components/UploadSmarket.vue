<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Upload your smarket csv file</div>
                    <div class="panel-body">
                        <input class="btn btn-default" type="file" @change="onFileChange">
                    </div>
                    <button class="btn btn-primary" @click="uploadFile">Upload</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                file: null
            }
        },
        methods: {
            onFileChange(e) {
                const files = e.target.files || e.dataTransfer.files;
                if (!files.length) {
                    return;
                }
                this.file = files[0];
            },
            uploadFile() {
                let formData = new FormData();
                formData.append("image", this.file);

                axios.post('api/upload-csv', formData, {
                    dataType: "text/csv",
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(function (response) {
                        console.log(response);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        }
    }
</script>