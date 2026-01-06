/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://github.com/mineadmin
 */
import type { MaProTableColumns, MaProTableExpose } from '@mineadmin/pro-table'
import type { UseDialogExpose } from '@/hooks/useDialog'
import { useMessage } from '@/hooks/useMessage'
import { deleteByIds } from '~/base/api/articleCategory'
import { ResultCode } from '@/utils/ResultCode'
import { updateStatusApi } from '@/modules/base/api/articleCategory'

export default function getTableColumns(
  dialog: UseDialogExpose,
  // formRef: any,
  // t: any,
): MaProTableColumns[] {
  const t = useTrans().globalTrans
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
    {
      label: '状态',
      prop: 'status',
      width: 100,
      cellRender: ({ row }) => {
        const loading = ref(false)

        const handleChange = async (val: boolean) => {
          const newStatus = val ? 1 : 0
          const oldStatus = row.status

          loading.value = true
          row.status = newStatus // 先乐观更新

          const res = await updateStatusApi({
            id: row.id,
            status: newStatus,
          })

          console.log('updateStatusApi 返回：', res)

          loading.value = false

          if (res.code !== 200) {
            row.status = oldStatus // 回滚
            msg.error(res.message)
            return
          }

          msg.success(t('articleCategory.statusUpdateSuccess'))
          window.__proTableRef?.refresh()
        }

        return (
          <el-switch
            modelValue={row.status === 1}
            onChange={handleChange}
            v-auth={['article:category:update']}
          />
        )
      },
    },

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
