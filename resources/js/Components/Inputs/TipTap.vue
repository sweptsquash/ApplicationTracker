<script lang="ts" setup>
import BoldExtenstion from '@tiptap/extension-bold'
import BulletList from '@tiptap/extension-bullet-list'
import { Color } from '@tiptap/extension-color'
import Document from '@tiptap/extension-document'
import DropCursor from '@tiptap/extension-dropcursor'
import FontFamilyExtension from '@tiptap/extension-font-family'
import GapCursor from '@tiptap/extension-gapcursor'
import Heading, { type Level } from '@tiptap/extension-heading'
import HighlightExtension from '@tiptap/extension-highlight'
import History from '@tiptap/extension-history'
import HorizontalRule from '@tiptap/extension-horizontal-rule'
import ItalicExtension from '@tiptap/extension-italic'
import LinkExtension from '@tiptap/extension-link'
import ListItem from '@tiptap/extension-list-item'
import OrderedList from '@tiptap/extension-ordered-list'
import Paragraph from '@tiptap/extension-paragraph'
import StrikeExtension from '@tiptap/extension-strike'
import SuperscriptExtension from '@tiptap/extension-subscript'
import SubscriptExtension from '@tiptap/extension-superscript'
import Text from '@tiptap/extension-text'
import TextAlign from '@tiptap/extension-text-align'
import TextStyle from '@tiptap/extension-text-style'
import Typography from '@tiptap/extension-typography'
import UnderlineExtension from '@tiptap/extension-underline'
import { EditorContent, useEditor } from '@tiptap/vue-3'
import {
  AlignCenter,
  AlignJustify,
  AlignLeft,
  AlignRight,
  Bold,
  EllipsisVertical,
  Italic,
  Link,
  List,
  ListOrdered,
  RemoveFormatting,
  Strikethrough,
  Subscript,
  Superscript,
  Underline,
} from 'lucide-vue-next'
import FontSizeExtension from 'tiptap-extension-font-size'

const props = withDefaults(
  defineProps<{
    modelValue: string
    label?: string
    required?: boolean
  }>(),
  {
    modelValue: '',
    label: undefined,
    required: false,
  },
)

const emits = defineEmits(['update:modelValue', 'onChange'])

const fontFamilies = [
  { label: 'Inter', value: 'Inter' },
  { label: 'Comic Sans', value: 'Comic Sans MS, Comic Sans' },
  { label: 'Serif', value: 'serif' },
  { label: 'Monospace', value: 'monospace' },
  { label: 'Cursive', value: 'cursive' },
]

const fontSizes = [8, 9, 10, 11, 12, 14, 18, 24, 30, 36, 48, 60, 72, 96]

const textStyles = [
  { label: 'Normal', value: 'normal' },
  { label: 'Heading 1', value: 1 },
  { label: 'Heading 2', value: 2 },
  { label: 'Heading 3', value: 3 },
  { label: 'Heading 4', value: 4 },
  { label: 'Heading 5', value: 5 },
  { label: 'Heading 6', value: 6 },
]

const showTextToolbar = ref(false)

const showParagraphToolbar = ref(false)

const displaySubtoolbar = computed(() => showTextToolbar.value || showParagraphToolbar.value)

const currentFontStyle = ref('normal')

const currentFontSize = ref(12)

const currentFontFamily = ref('serif')

const editor = useEditor({
  extensions: [
    BoldExtenstion,
    BulletList,
    Color,
    Document,
    DropCursor,
    FontFamilyExtension,
    FontSizeExtension,
    GapCursor,
    Heading,
    HighlightExtension,
    History,
    HorizontalRule,
    ItalicExtension,
    LinkExtension.configure({
      defaultProtocol: 'https',
    }),
    ListItem,
    OrderedList,
    Paragraph,
    StrikeExtension,
    SubscriptExtension,
    SuperscriptExtension,
    Text,
    TextAlign,
    TextStyle.configure({ mergeNestedSpanStyles: true }),
    Typography,
    UnderlineExtension,
  ],
  onUpdate: () => {
    emits('update:modelValue', editor.value?.isEmpty ? '' : editor.value?.getHTML())
    emits('onChange', { value: editor.value?.isEmpty ? '' : editor.value?.getHTML() })
  },
  onSelectionUpdate: (event) => {
    editor.value?.state?.doc?.nodesBetween(
      /* @ts-expect-error curSelection not added to type definition */
      event.transaction.curSelection.from,
      /* @ts-expect-error curSelection not added to type definition */
      event.transaction.curSelection.to,
      (node) => {
        if (node.type.name === 'heading') {
          currentFontStyle.value = node.attrs.level
        } else {
          currentFontStyle.value = 'normal'
        }

        if (node.marks.length > 0) {
          const fontSize =
            node.marks
              .find((mark) => Object.prototype.hasOwnProperty.call(mark.attrs, 'fontSize'))
              ?.attrs?.fontSize?.replace('pt', '') ?? '12'

          const fontFamily =
            node.marks
              .find((mark) => Object.prototype.hasOwnProperty.call(mark.attrs, 'fontFamily'))
              ?.attrs?.fontFamily?.replace(/"/g, '') ?? 'serif'

          if (fontSize) {
            currentFontSize.value = parseInt(fontSize)
          }

          if (fontFamily) {
            currentFontFamily.value = fontFamily
          }
        } else {
          currentFontSize.value = 12
          currentFontFamily.value = 'serif'
        }
      },
    )
  },
})

onMounted(() => {
  editor.value?.commands?.setContent(props.modelValue, false)
})

watch(
  () => props.modelValue,
  (value) => {
    if (editor.value === undefined) {
      return
    }

    const isSame = editor.value?.getHTML() === value

    if (isSame) {
      return
    }

    editor.value?.commands?.setContent(value, false)
  },
  { deep: true, immediate: true },
)

function toggleTextToolbar() {
  showTextToolbar.value = !showTextToolbar.value

  if (showParagraphToolbar.value) {
    showParagraphToolbar.value = false
  }
}

function toggleParagraphToolbar() {
  showParagraphToolbar.value = !showParagraphToolbar.value

  if (showTextToolbar.value) {
    showTextToolbar.value = false
  }
}

function changeFontStyle(value: string | Level) {
  let fontLevel: number = 1

  if (!isNaN(Number(value)) && isFinite(Number(value)) && typeof value === 'string') {
    fontLevel = parseInt(value)
  }

  if (value === 'normal') {
    editor.value?.chain()?.focus()?.clearNodes()?.run()
  } else {
    editor.value
      ?.chain()
      ?.focus()
      ?.toggleHeading({ level: fontLevel as Level })
      ?.run()
  }
}

function setLink() {
  if (editor.value === undefined) {
    return
  }

  const previousUrl = editor.value.getAttributes('link').href
  const url = window.prompt('URL', previousUrl)

  if (url === null) {
    return
  }

  if (url === '') {
    editor.value.chain().focus().extendMarkRange('link').unsetLink().run()

    return
  }

  editor.value.chain().focus().extendMarkRange('link').setLink({ href: url }).run()
}
</script>

<template>
  <div>
    <label
      v-if="label"
      for="editor"
      :class="['block text-sm/6 font-medium text-gray-900 dark:text-white', { required }]"
    >
      {{ label }}
    </label>
    <div class="flex flex-col">
      <div class="tiptap-toolbar">
        <button
          v-tooltip.bottom="'Bold'"
          class="btn"
          aria-label="Bold"
          @click="editor?.chain()?.focus()?.toggleBold()?.run()"
        >
          <Bold class="h-4 w-4" />
        </button>

        <button
          v-tooltip.bottom="'Italic'"
          class="btn"
          aria-label="Italic"
          @click="editor?.chain()?.focus()?.toggleItalic()?.run()"
        >
          <Italic class="h-4 w-4" />
        </button>

        <button
          v-tooltip.bottom="'Underline'"
          class="btn"
          aria-label="Underline"
          @click="editor?.chain()?.focus()?.toggleUnderline()?.run()"
        >
          <Underline class="h-4 w-4" />
        </button>

        <button
          v-tooltip.bottom="'More text'"
          class="btn"
          aria-label="More options"
          @click="toggleTextToolbar"
        >
          <EllipsisVertical class="h-4 w-4" />
        </button>

        <div class="vertical-divider"></div>

        <button
          v-tooltip.bottom="'Align left'"
          class="btn"
          aria-label="Align left"
          @click="editor?.chain()?.focus()?.setTextAlign('left')?.run()"
        >
          <AlignLeft class="h-4 w-4" />
        </button>

        <button
          v-tooltip.bottom="'Align center'"
          class="btn"
          aria-label="Align center"
          @click="editor?.chain()?.focus()?.setTextAlign('center')?.run()"
        >
          <AlignCenter class="h-4 w-4" />
        </button>

        <button
          v-tooltip.bottom="'Align right'"
          class="btn"
          aria-label="Align right"
          @click="editor?.chain()?.focus()?.setTextAlign('right')?.run()"
        >
          <AlignRight class="h-4 w-4" />
        </button>

        <button
          v-tooltip.bottom="'More paragraph'"
          class="btn"
          aria-label="More paragraph"
          @click="toggleParagraphToolbar"
        >
          <EllipsisVertical class="h-4 w-4" />
        </button>

        <div class="vertical-divider"></div>

        <button
          v-tooltip.bottom="'Insert link'"
          class="btn"
          aria-label="Insert link"
          @click="setLink"
        >
          <Link class="h-4 w-4" />
        </button>
      </div>
      <div v-if="displaySubtoolbar" class="tiptap-sub-toolbar">
        <template v-if="showTextToolbar">
          <button
            v-tooltip.bottom="'Strikethrough'"
            class="btn"
            aria-label="Strikethrough"
            @click="editor?.chain()?.focus()?.toggleStrike()?.run()"
          >
            <Strikethrough class="h-4 w-4" />
          </button>
          <button
            v-tooltip.bottom="'Superscript'"
            class="btn"
            aria-label="Superscript"
            @click="editor?.chain()?.focus()?.toggleSuperscript()?.run()"
          >
            <Superscript class="h-4 w-4" />
          </button>
          <button
            v-tooltip.bottom="'Subscript'"
            class="btn"
            aria-label="Subscript"
            @click="editor?.chain()?.focus()?.toggleSubscript()?.run()"
          >
            <Subscript class="h-4 w-4" />
          </button>

          <select
            v-model="currentFontFamily"
            class="select"
            @change="
              editor
                ?.chain()
                ?.focus()
                ?.setFontFamily(($event.target as HTMLSelectElement)?.value)
                ?.run()
            "
          >
            <option
              v-for="fontFamily in fontFamilies"
              :key="fontFamily.value"
              :value="fontFamily.value"
            >
              {{ fontFamily.label }}
            </option>
          </select>

          <select
            v-model="currentFontSize"
            class="select"
            @change="
              editor
                ?.chain()
                ?.focus()
                ?.setFontSize(`${($event.target as HTMLSelectElement)?.value}pt`)
                ?.run()
            "
          >
            <option v-for="fontSize in fontSizes" :key="fontSize" :value="fontSize">
              {{ fontSize }}
            </option>
          </select>

          <button
            v-tooltip.bottom="'Remove formatting'"
            class="btn"
            aria-label="Remove formatting"
            @click="editor?.chain()?.focus()?.unsetAllMarks()?.run()"
          >
            <RemoveFormatting class="h-4 w-4" />
          </button>
        </template>
        <template v-if="showParagraphToolbar">
          <button
            v-tooltip.bottom="'Align justify'"
            class="btn"
            aria-label="Align justify"
            @click="editor?.chain()?.focus()?.setTextAlign('justify')?.run()"
          >
            <AlignJustify class="h-4 w-4" />
          </button>
          <button
            v-tooltip.bottom="'List'"
            class="btn"
            aria-label="List"
            @click="editor?.chain()?.focus()?.toggleBulletList()?.run()"
          >
            <List class="h-4 w-4" />
          </button>
          <button
            v-tooltip.bottom="'Ordered List'"
            class="btn"
            aria-label="Ordered List"
            @click="editor?.chain()?.focus()?.toggleOrderedList()?.run()"
          >
            <ListOrdered class="h-4 w-4" />
          </button>

          <select
            v-model="currentFontStyle"
            class="select"
            @change="changeFontStyle(($event.target as HTMLSelectElement)?.value)"
          >
            <option v-for="textStyle in textStyles" :key="textStyle.value" :value="textStyle.value">
              {{ textStyle.label }}
            </option>
          </select>
        </template>
      </div>
      <EditorContent :editor="editor" class="block" />
    </div>
  </div>
</template>
