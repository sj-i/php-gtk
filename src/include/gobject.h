extern
guint g_signal_newv (const gchar *signal_name,
          GType itype,
          GSignalFlags signal_flags,
          GClosure *class_closure,
          GSignalAccumulator accumulator,
          gpointer accu_data,
          GSignalCMarshaller c_marshaller,
          GType return_type,
          guint n_params,
          GType *param_types);
extern
guint g_signal_new_valist (const gchar *signal_name,
          GType itype,
          GSignalFlags signal_flags,
          GClosure *class_closure,
          GSignalAccumulator accumulator,
          gpointer accu_data,
          GSignalCMarshaller c_marshaller,
          GType return_type,
          guint n_params,
          va_list args);
extern
guint g_signal_new (const gchar *signal_name,
          GType itype,
          GSignalFlags signal_flags,
          guint class_offset,
          GSignalAccumulator accumulator,
          gpointer accu_data,
          GSignalCMarshaller c_marshaller,
          GType return_type,
          guint n_params,
          ...);
extern
guint g_signal_new_class_handler (const gchar *signal_name,
                                             GType itype,
                                             GSignalFlags signal_flags,
                                             GCallback class_handler,
                                             GSignalAccumulator accumulator,
                                             gpointer accu_data,
                                             GSignalCMarshaller c_marshaller,
                                             GType return_type,
                                             guint n_params,
                                             ...);
extern
void g_signal_set_va_marshaller (guint signal_id,
          GType instance_type,
          GSignalCVaMarshaller va_marshaller);
extern
void g_signal_emitv (const GValue *instance_and_params,
          guint signal_id,
          GQuark detail,
          GValue *return_value);
extern
void g_signal_emit_valist (gpointer instance,
          guint signal_id,
          GQuark detail,
          va_list var_args);
extern
void g_signal_emit (gpointer instance,
          guint signal_id,
          GQuark detail,
          ...);
extern
void g_signal_emit_by_name (gpointer instance,
          const gchar *detailed_signal,
          ...);
extern
guint g_signal_lookup (const gchar *name,
          GType itype);
extern
const gchar * g_signal_name (guint signal_id);
extern
void g_signal_query (guint signal_id,
          GSignalQuery *query);
extern
guint* g_signal_list_ids (GType itype,
          guint *n_ids);
extern
gboolean g_signal_parse_name (const gchar *detailed_signal,
          GType itype,
          guint *signal_id_p,
          GQuark *detail_p,
          gboolean force_detail_quark);
extern
GSignalInvocationHint* g_signal_get_invocation_hint (gpointer instance);
extern
void g_signal_stop_emission (gpointer instance,
          guint signal_id,
          GQuark detail);
extern
void g_signal_stop_emission_by_name (gpointer instance,
          const gchar *detailed_signal);
extern
gulong g_signal_add_emission_hook (guint signal_id,
          GQuark detail,
          GSignalEmissionHook hook_func,
          gpointer hook_data,
          GDestroyNotify data_destroy);
extern
void g_signal_remove_emission_hook (guint signal_id,
          gulong hook_id);
extern
gboolean g_signal_has_handler_pending (gpointer instance,
            guint signal_id,
            GQuark detail,
            gboolean may_be_blocked);
extern
gulong g_signal_connect_closure_by_id (gpointer instance,
            guint signal_id,
            GQuark detail,
            GClosure *closure,
            gboolean after);
extern
gulong g_signal_connect_closure (gpointer instance,
            const gchar *detailed_signal,
            GClosure *closure,
            gboolean after);
extern
gulong g_signal_connect_data (gpointer instance,
            const gchar *detailed_signal,
            GCallback c_handler,
            gpointer data,
            GClosureNotify destroy_data,
            GConnectFlags connect_flags);
extern
void g_signal_handler_block (gpointer instance,
            gulong handler_id);
extern
void g_signal_handler_unblock (gpointer instance,
            gulong handler_id);
extern
void g_signal_handler_disconnect (gpointer instance,
            gulong handler_id);
extern
gboolean g_signal_handler_is_connected (gpointer instance,
            gulong handler_id);
extern
gulong g_signal_handler_find (gpointer instance,
            GSignalMatchType mask,
            guint signal_id,
            GQuark detail,
            GClosure *closure,
            gpointer func,
            gpointer data);
extern
guint g_signal_handlers_block_matched (gpointer instance,
            GSignalMatchType mask,
            guint signal_id,
            GQuark detail,
            GClosure *closure,
            gpointer func,
            gpointer data);
extern
guint g_signal_handlers_unblock_matched (gpointer instance,
            GSignalMatchType mask,
            guint signal_id,
            GQuark detail,
            GClosure *closure,
            gpointer func,
            gpointer data);
extern
guint g_signal_handlers_disconnect_matched (gpointer instance,
            GSignalMatchType mask,
            guint signal_id,
            GQuark detail,
            GClosure *closure,
            gpointer func,
            gpointer data);
extern
void g_clear_signal_handler (gulong *handler_id_ptr,
            gpointer instance);
extern
void g_signal_override_class_closure (guint signal_id,
                                               GType instance_type,
                                               GClosure *class_closure);
extern
void g_signal_override_class_handler (const gchar *signal_name,
                                               GType instance_type,
                                               GCallback class_handler);
extern
void g_signal_chain_from_overridden (const GValue *instance_and_params,
                                               GValue *return_value);
extern
void g_signal_chain_from_overridden_handler (gpointer instance,
                                               ...);
extern
gboolean g_signal_accumulator_true_handled (GSignalInvocationHint *ihint,
         GValue *return_accu,
         const GValue *handler_return,
         gpointer dummy);
extern
gboolean g_signal_accumulator_first_wins (GSignalInvocationHint *ihint,
                                            GValue *return_accu,
                                            const GValue *handler_return,
                                            gpointer dummy);
extern
void g_signal_handlers_destroy (gpointer instance);