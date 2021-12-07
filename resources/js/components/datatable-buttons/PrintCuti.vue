<template>
    <button class="btn btn-sm btn-success" @click="requestPrint">
        <slot>Print</slot>
    </button>
</template>

<script>
import axios from 'axios'
import eventbus from '../../eventbus'
    export default{
        props: ['nip', 'no_cuti'],
        data(){
            return{
                print:{
                    nip:this.nip,
                    no_cuti:this.no_cuti
                }
            }
        },
        methods: {
            requestPrint() {
                var fname = 'Surat Cuti - '+this.nip+'.pdf'
                axios.post('/form/print',this.print,{
                    responseType:'arraybuffer'
                })
                .then(resp => {
                        eventbus.$emit('loading-start',{message: 'Memuat dokumen...'});
                        console.log('status : '+resp.data.status);
                    
                        const url = window.URL.createObjectURL(new Blob([resp.data],{type:'application/pdf'}));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download',fname);
                        document.body.appendChild(link);
                        link.click();
                        eventbus.$emit('loading-end');
                    
                })
                .catch(err =>{
                    console.log('error print : '+err);
                })
            },
            // testPrint(){
            //     axios.get('/print',{responseType:'arraybuffer'})
            //     .then(resp => {
            //         console.log("test data : "+resp.status);
            //         console.log("data null ? "+resp.data == null)
            //             let blob = new Blob([resp.data],{type:'application/pdf'});
            //             const url = window.URL.createObjectURL(blob);
            //             const link = document.createElement('a');
            //             link.href = url;
            //             link.setAttribute('download','test print.pdf');
            //             document.body.appendChild(link);
            //             link.click();
            //     })
            //     .catch(err =>{
            //         alert(err);
            //     })
            // }
        },
    }
</script>