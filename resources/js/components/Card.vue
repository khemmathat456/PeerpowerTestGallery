<template>
    <div class="container" style="padding-bottom: 1.5rem">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <slot name="header"></slot>
                    </div>

                    <div class="card-body">
                        <slot name="body"></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {

            }
        }

    }
</script>

