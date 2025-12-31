import type { PageList, ResponseStruct } from '#/global'

export interface ArticleCategoryVo {
  id?: number
  parent_id?: number
  name?: string
  sort?: number
  status?: number
  remark?: string
  path?: string
  created_at?: string
}

export interface ArticleCategorySearchVo {
  name?: string
  status?: number
  [key: string]: any
}

export function tree() {
  return useHttp().get('/admin/article/category/tree')
}

export function page(params: ArticleCategorySearchVo | null = null): Promise<ResponseStruct<PageList<ArticleCategoryVo>>> {
  return useHttp().get('/admin/article/category/list', { params })
}

export function create(data: ArticleCategoryVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/article/category', data)
}

export function save(id: number, data: ArticleCategoryVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/article/category/${id}`, data)
}

export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/article/category', { data: ids })
}
