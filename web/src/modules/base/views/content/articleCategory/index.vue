<script setup lang="tsx">
import type { MaProTableExpose, MaProTableOptions, MaProTableSchema } from '@mineadmin/pro-table'
import type { Ref } from 'vue'
import type { TransType } from '@/hooks/auto-imports/useTrans'

import { page, deleteByIds } from '~/base/api/articleCategory'
import useDialog from '@/hooks/useDialog'
import { useMessage } from '@/hooks/useMessage'
import { ResultCode } from '@/utils/ResultCode'

import ArticleCategoryForm from './form.vue'
import getTableColumns from './data/getTableColumns'
import getSearchItems from './data/getSearchItems'

defineOptions({ name: 'content:articleCategory' })

const proTableRef = ref<MaProTableExpose>() as Ref<MaProTableExpose>
const formRef = ref()
const selections = ref<any[]>([])

/** 当前父级（列表只显示这一层） */
const parentId = ref<number>(0)

const i18n = useTrans() as TransType
const t = i18n.globalTrans
const msg = useMessage()

/* 弹窗 */
const maDialog = useDialog({
  lgWidth: '520px',
  ok: ({ formType }, okLoadingState: (state: boolean) => void) => {
    okLoadingState(true)
    const elForm = formRef.value?.maForm?.getElFormRef()
    if (!elForm) {
      okLoadingState(false)
      return
    }

    elForm.validate().then(() => {
      const action = formType === 'add'
        ? formRef.value.add()
        : formRef.value.edit()

      action.then((res: any) => {
        res.code === ResultCode.SUCCESS
          ? msg.success(formType === 'add' ? t('crud.createSuccess') : t('crud.updateSuccess'))
          : msg.error(res.message)

        maDialog.close()
        proTableRef.value.refresh()
      })
    }).finally(() => okLoadingState(false))
  },
})

/* 参数配置 */
const options = ref<MaProTableOptions>({
  adaptionOffsetBottom: 160,
  header: {
    mainTitle: () => '文章分类',
    subTitle: () => 'Article Categories',
  },
  tableOptions: {
    rowKey: 'id',
    on: {
      onSelectionChange: (selection: any[]) => selections.value = selection,
    },
  },
  searchOptions: {
    fold: true,
    text: {
      searchBtn: () => t('crud.search'),
      resetBtn: () => t('crud.reset'),
    },
  },
  // ✅ 这里只保留 labelWidth，和登录日志一模一样
  searchFormOptions: {
    labelWidth: '80px',
  },
  requestOptions: {
    api: (params: any) =>
      page({
        ...params,
        parent_id: parentId.value,
      }),
  },
})

const schema = ref<MaProTableSchema>({
  searchItems: getSearchItems(t),
  tableColumns: getTableColumns(maDialog, formRef, t),
})

/* 删除 */
function handleDelete() {
  const ids = selections.value.map(item => item.id)
  msg.confirm(t('crud.delMessage')).then(async () => {
    const res = await deleteByIds(ids)
    if (res.code === ResultCode.SUCCESS) {
      msg.success(t('crud.delSuccess'))
      proTableRef.value.refresh()
    }
  })
}
</script>

<template>
  <div class="mine-layout pt-3">
    <MaProTable ref="proTableRef" :options="options" :schema="schema">
      <template #actions>
        <el-button
          v-auth="['article:category:save']"
          type="primary"
          @click="() => {
            maDialog.setTitle(t('crud.add'))
            maDialog.open({
              formType: 'add',
              data: { parent_id: parentId },
            })
          }"
        >
          {{ t('crud.add') }}
        </el-button>
      </template>

      <template #toolbarLeft>
        <el-button
          v-auth="['article:category:delete']"
          type="danger"
          plain
          :disabled="selections.length < 1"
          @click="handleDelete"
        >
          {{ t('crud.delete') }}
        </el-button>
      </template>
    </MaProTable>

    <component :is="maDialog.Dialog">
      <template #default="{ formType, data }">
        <ArticleCategoryForm
          v-if="['add', 'edit'].includes(formType)"
          ref="formRef"
          :form-type="formType"
          :data="data"
        />
      </template>
    </component>
  </div>
</template>
