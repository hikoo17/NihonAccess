import { ref } from 'vue'
import { teacherApi } from '@/services/teacherApi'

const courses = ref([])

export function useTeacherOptions() {
  const fetchCourses = async () => {
    const res = await teacherApi.courses.list({ per_page: 100 })
    courses.value = res.data
    return res.data
  }

  const fetchLessonsFor = async (courseId) => {
    if (!courseId) return []
    const res = await teacherApi.courses.get(courseId)
    return res.data.lessons || []
  }

  return { courses, fetchCourses, fetchLessonsFor }
}
