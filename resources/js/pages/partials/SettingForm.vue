<script setup lang="ts">
import { Label } from '@/components/ui/label'
import { Switch } from '@/components/ui/switch'
import { Button } from '@/components/ui/button'
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { useForm } from '@inertiajs/vue3'
import { Loader2 } from 'lucide-vue-next'
import { useToast } from '@/components/ui/toast'

const props = defineProps<{
    setting: Object;
}>();

const { toast } = useToast()

const form = useForm({
    receiver_email: props.setting.receiver_email,
    invert_code: Boolean(props.setting.invert_code),
    secure_api: Boolean(props.setting.secure_api),
})

const submit = () => {
    form.post(route('setting.update'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            toast({
                title: page.props.flash.success,
            })
        },
        onError: (errors) => {
            toast({
                title: 'Failed to update setting',
                variant: 'destructive',
                description: errors,
            })
            console.log("Errors:", errors)
        }
    })
}
</script>

<template>
    <Card class="w-full">
        <CardHeader>
            <CardTitle>Setting Form</CardTitle>
            <CardDescription>Manage your settings here.</CardDescription>
        </CardHeader>
        <CardContent>
            <form>
                <div class="grid items-center w-full gap-4">
                    <div class="flex flex-col space-y-1.5">
                        <Label for="receiver_email">Receiver Email</Label>
                        <Input id="receiver_email" v-model="form.receiver_email" placeholder="Enter receiver email" />
                    </div>
                    <div class="flex items-center space-x-2">
                        <Label for="invert_code">Invert Code</Label>
                        <Switch id="invert_code" v-model="form.invert_code" />
                    </div>
                    <div class="flex items-center space-x-2">
                        <Label for="secure_api">Secure API</Label>
                        <Switch id="secure_api" v-model="form.secure_api" />
                    </div>
                </div>
            </form>
        </CardContent>
        <CardFooter class="flex justify-end px-6 pb-6">
            <Button @click="submit" type="button">
                <Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                {{ form.processing ? 'Updating...' : 'Update' }}
            </Button>
        </CardFooter>
    </Card>
</template>