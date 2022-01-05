package com.example.banqueenligne.fragment

import android.content.Intent
import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.LinearLayout
import android.widget.TextView
import com.example.banqueenligne.MakeVirement
import com.example.banqueenligne.MesBeneficiaires
import com.example.banqueenligne.R
import com.google.android.flexbox.FlexboxLayout

// TODO: Rename parameter arguments, choose names that match
// the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
private const val ARG_PARAM1 = "param1"
private const val ARG_PARAM2 = "param2"

/**
 * A simple [Fragment] subclass.
 * Use the [VirementFragment.newInstance] factory method to
 * create an instance of this fragment.
 */
class VirementFragment : Fragment(), View.OnClickListener {
    // TODO: Rename and change types of parameters
    private var param1: String? = null
    private var param2: String? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        arguments?.let {
            param1 = it.getString(ARG_PARAM1)
            param2 = it.getString(ARG_PARAM2)
        }
    }

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        val view = inflater.inflate(R.layout.fragment_virement, container, false)
        val b = view.findViewById<TextView>(R.id.virement)
        b.setOnClickListener(this)
        val beneficiaire = view.findViewById<FlexboxLayout>(R.id.beneficiaires)
        beneficiaire.setOnClickListener(this)

        // Inflate the layout for this fragment
//        return inflater.inflate(R.layout.fragment_virement, container, false)
        return view
    }

    override fun onClick(view: View) {
        when (view.getId()) {
            R.id.virement -> startActivity(Intent(context, MakeVirement::class.java))
            R.id.beneficiaires -> startActivity(Intent(context, MesBeneficiaires::class.java))
        }
    }

    companion object {
        /**
         * Use this factory method to create a new instance of
         * this fragment using the provided parameters.
         *
         * @param param1 Parameter 1.
         * @param param2 Parameter 2.
         * @return A new instance of fragment VirementFragment.
         */
        // TODO: Rename and change types and number of parameters
        @JvmStatic
        fun newInstance(param1: String, param2: String) =
            VirementFragment().apply {
                arguments = Bundle().apply {
                    putString(ARG_PARAM1, param1)
                    putString(ARG_PARAM2, param2)
                }
            }
    }
}