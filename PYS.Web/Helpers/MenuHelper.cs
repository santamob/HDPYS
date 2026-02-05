using Microsoft.AspNetCore.Mvc.Rendering;
using System.Linq;

namespace PYS.Web.Helpers
{
    public static class MenuHelper
    {
        public static bool IsController(this ViewContext context, string controller)
        {
            var current = context.RouteData.Values["controller"]?.ToString();
            return string.Equals(current, controller, System.StringComparison.OrdinalIgnoreCase);
        }

        public static bool IsControllerIn(this ViewContext context, params string[] controllers)
        {
            var current = context.RouteData.Values["controller"]?.ToString();
            return controllers.Contains(current);
        }

        public static bool IsAction(this ViewContext context, string action)
        {
            var current = context.RouteData.Values["action"]?.ToString();
            return string.Equals(current, action, System.StringComparison.OrdinalIgnoreCase);
        }

        public static bool IsActive(this ViewContext context, string controller, string action = null)
        {
            var isControllerMatch = context.IsController(controller);
            if (string.IsNullOrEmpty(action))
                return isControllerMatch;

            return isControllerMatch && context.IsAction(action);
        }
    }
}
