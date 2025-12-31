import type { MaFormItem } from '@mineadmin/form'
import { ref } from 'vue'

export default function getFormItems(
  formType: 'add' | 'edit',
  model: any,
): MaFormItem[] {

  const treeData = ref<any[]>(model._treeData || [])

  return [
    // 上级分类
    {
      label: '上级分类',
      prop: 'parent_id',
      render: () => (
        <el-tree-select
          modelValue={model.parent_id}
          onUpdate:modelValue={(val: number | null) => {
            model.parent_id = val
          }}
          data={treeData.value}
          props={{ value: 'id', label: 'name' }}
          clearable
          check-strictly
          placeholder="请选择上级分类"
        />
      ),
      renderProps: {
        class: 'w-full',
      },
    },

    // 分类名称
    {
      label: '分类名称',
      prop: 'name',
      render: 'input',
      renderProps: {
        placeholder: '请输入分类名称',
      },
      itemProps: {
        rules: [{ required: true, message: '请输入分类名称' }],
      },
    },

    // 状态
    {
      label: '状态',
      prop: 'status',
      render: () => (
        <el-radio-group
          modelValue={model.status}
          onUpdate:modelValue={(val: number) => {
            model.status = val
          }}
        >
          <el-radio label={1}>启用</el-radio>
          <el-radio label={0}>禁用</el-radio>
        </el-radio-group>
      ),
    },

    // 排序
    {
      label: '排序',
      prop: 'sort',
      render: 'inputNumber',
      renderProps: {
        min: 0,
        class: 'w-full',
      },
    },

    // 备注
    {
      label: '备注',
      prop: 'remark',
      render: () => (
        <el-input
          type="textarea"
          rows={3}
          placeholder="请输入备注"
          modelValue={model.remark}
          onUpdate:modelValue={(val: string) => {
            model.remark = val
          }}
        />
      ),
    },

  ]
}
