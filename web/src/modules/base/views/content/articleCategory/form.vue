<!--
 - MineAdmin is committed to providing solutions for quickly building web applications
 - Please view the LICENSE file that was distributed with this source code,
 - For the full copyright and license information.
 - Thank you very much for using MineAdmin.
 -
 - @Author X.Mo<root@imoi.cn>
 - @Link   https://github.com/mineadmin
-->

<script setup lang="ts">
import type { MaFormExpose } from '@mineadmin/form'
import useForm from '@/hooks/useForm'
import { useMessage } from '@/hooks/useMessage'
import { ResultCode } from '@/utils/ResultCode'
import getFormItems from './data/getFormItems'

// ⭐ 只新增这一行（引入 API）
import { create, save, tree } from '~/base/api/articleCategory'

defineOptions({ name: 'content:articleCategory:form' })

const { formType = 'add', data = null } = defineProps<{
  formType: 'add' | 'edit'
  data?: any
}>()

const msg = useMessage()
const categoryForm = ref<MaFormExpose>()

/**
 * ⚠️ 关键点：
 * parent_id 一开始必须是 null
 */
const model = ref<any>({
  parent_id: null,
  name: '',
  status: 1,
  sort: 0,
  remark: '',
})

useForm('categoryForm').then(async (form: MaFormExpose) => {
  // ⭐ 加载父级分类树
  const res = await tree()
  model.value._treeData = [
    {
      id: 0,
      name: '请选择上级分类',
      children: [],
    },
    ...res.data,
  ]

  // ⭐ 编辑：回显旧值（原样用后端给的）
  if (formType === 'edit' && data) {
    Object.assign(model.value, data)
  }

  // ⭐ 新增：选中当前查看层级（列表当前的 parent_id），不判断大小
  if (formType === 'add' && data && 'parent_id' in data) {
    model.value.parent_id = data.parent_id
  }

  // ⭐ 重新渲染表单项
  form.setItems(getFormItems(formType, model.value))
  form.setOptions({
    labelWidth: '80px',
  })
})

/**
 * 提交前兜底
 * UI 没选 = null
 * 业务必须是 0
 */
function normalize() {
  if (model.value.parent_id === null) {
    model.value.parent_id = 0
  }
}

/**
 * ⭐ 只改这里：新增 → 调后端 create()
 */
function add() {
  normalize()
  return create(model.value)
}

/**
 * ⭐ 只改这里：编辑 → 调后端 save()
 */
function edit() {
  normalize()
  return save(model.value.id, model.value)
}

defineExpose({
  add,
  edit,
  maForm: categoryForm,
})
</script>

<template>
  <ma-form ref="categoryForm" v-model="model" />
</template>
