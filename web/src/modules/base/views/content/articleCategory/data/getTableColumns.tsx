import type { MaProTableColumns, MaProTableExpose } from '@mineadmin/pro-table'
import type { UseDialogExpose } from '@/hooks/useDialog'
import { useMessage } from '@/hooks/useMessage'
import { deleteByIds } from '~/base/api/articleCategory'
import { ResultCode } from '@/utils/ResultCode'

export default function getTableColumns(
  dialog: UseDialogExpose,
  formRef: any,
  t: any,
): MaProTableColumns[] {
  const msg = useMessage()

  return [
    // 多选列
    { type: 'selection', width: 50 },

    // 普通字段（除 deleted_at 外全部）
    { label: 'ID', prop: 'id', width: 80 },

    {
      label: '分类名称',
      prop: 'name',
      width: 140,
      cellRender: ({ row }) => {
        return (
          <a
            style="color:#409EFF;cursor:pointer;"
            onClick={() => window.__goNextCategory(row.id, row.name)}
          >
            {row.name}
          </a>
        )
      },
    },

    { label: '父级ID', prop: 'parent_id', width: 140 },
    { label: '排序', prop: 'sort', width: 80 },
    { label: '状态', prop: 'status', width: 100 },
    { label: '备注', prop: 'remark', minWidth: 100 },
    { label: '创建时间', prop: 'created_at', width: 180 },
    { label: '更新时间', prop: 'updated_at', width: 180 },

    // 操作列
    {
      type: 'operation',
      label: '操作',
      width: 120,
      operationConfigure: {
        actions: [
          {
            name: 'edit',
            text: '编辑',
            icon: 'material-symbols:edit',
            onClick: ({ row }) => {
              dialog.setTitle('编辑分类')
              dialog.open({
                formType: 'edit',
                data: row,
              })
            },
          },
          {
            name: 'delete',
            text: '删除',
            icon: 'mdi:delete',
            onClick: ({ row }, proxy: MaProTableExpose) => {
              msg.delConfirm('确认删除该分类？').then(async () => {
                const res = await deleteByIds([row.id])
                if (res.code === ResultCode.SUCCESS) {
                  msg.success('删除成功')
                  proxy.refresh()
                }
              })
            },
          },
        ],
      },
    },
  ]
}
